<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    protected PropertyService $service;

    public function __construct(PropertyService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        try {
            $includeTrashed = $request->query('trashed') === 'true' && auth()->check();
            $locale = app()->getLocale();

            // Build base query and include relations
            $query = Property::with(['translations', 'images'])->orderBy('created_at', 'desc');

            if ($includeTrashed) {
                $query->withTrashed();
            }

            // Only include properties that have a translation for the current locale
            $query->whereHas('translations', function ($q) use ($locale) {
                $q->where('locale', $locale);
            });

            // Apply price range filter
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            // Apply room count filter
            if ($request->filled('room_count')) {
                $room_count = is_array($request->input('room_count'))
                    ? $request->input('room_count')
                    : [$request->input('room_count')];
                $query->whereIn('rooms', $room_count);
            }

            // Apply garage count filter
            if ($request->filled('garage_count')) {
                $garage_count = is_array($request->input('garage_count'))
                    ? $request->input('garage_count')
                    : [$request->input('garage_count')];
                $query->whereIn('garages', $garage_count);
            }

            // Apply region filter
            if ($request->filled('regions')) {
                $regions = is_array($request->input('regions'))
                    ? $request->input('regions')
                    : [$request->input('regions')];
                $query->whereIn('region', $regions);
            }

            // Apply sorting
            $sort = $request->input('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            // Paginate results (10 per page) and preserve query string parameters
            $properties = $query->paginate(10)->appends($request->query());

            // Get search parameters for the filter UI
            $maxPrice = Property::max('price') ?? 1000000;
            $maxRoomCount = Property::max('rooms') ?? 5;
            $maxGarageCount = Property::max('garages') ?? 3;
            $regions = Property::select('region')
                ->distinct()
                ->whereNotNull('region')
                ->where('region', '!=', '')
                ->orderBy('region')
                ->pluck('region')
                ->values();

            return view('properties.index', compact(
                'properties',
                'includeTrashed',
                'maxPrice',
                'maxRoomCount',
                'maxGarageCount',
                'regions',
                'locale'
            ));
        } catch (\Exception $e) {
            \Log::error('Property index error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback: Return empty properties list
            return view('properties.index', [
                'properties' => collect(),
                'includeTrashed' => false,
                'maxPrice' => 1000000,
                'maxRoomCount' => 5,
                'maxGarageCount' => 3,
                'regions' => collect(),
                'locale' => app()->getLocale(),
            ])->with('error', __('An error occurred while filtering properties.'));
        }
    }

    public function show(Property $property): View
    {
        $property->load(['translations', 'images']);
        $locale = app()->getLocale();
        $translation = $property->getTranslation($locale);

        return view('property.show', compact('property', 'translation'));
    }

    public function create(): View
    {
        $this->authorize('create', Property::class);

        $locales = config('localization.supported_locales', ['en', 'fr', 'fa']);
        $types = ['apartment', 'property', 'villa', 'studio', 'land', 'commercial'];
        $statuses = ['available', 'reserved', 'sold', 'rented'];

        return view('properties.create', compact('locales', 'types', 'statuses'));
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $this->authorize('create', Property::class);

        $property = $this->service->storeProperty($request->validated());

        return redirect()
            ->route('properties.index', ['locale' => app()->getLocale()])
            ->with('success', __('messages.property_saved'));
    }

    public function edit(Property $property): View
    {
        $this->authorize('update', $property);

        $property->load(['translations', 'images']);
        $locales = config('localization.supported_locales', ['en', 'fr', 'fa']);
        $types = ['apartment', 'property', 'villa', 'studio', 'land', 'commercial'];
        $statuses = ['available', 'reserved', 'sold', 'rented'];

        return view('properties.edit', compact('property', 'locales', 'types', 'statuses'));
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        $this->service->updateProperty($property, $request->validated());

        return redirect()
            ->route('properties.index', ['locale' => app()->getLocale()])
            ->with('success', __('messages.property_updated'));
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->authorize('delete', $property);

        $this->service->deleteProperty($property);

        return redirect()
            ->route('properties.index', ['locale' => app()->getLocale()])
            ->with('success', __('messages.property_deleted'));
    }

    public function restore(string $id): RedirectResponse
    {
        $this->authorize('restore', Property::class);

        $property = $this->service->restoreProperty($id);

        if ($property) {
            return redirect()
                ->route('properties.index', ['locale' => app()->getLocale()])
                ->with('success', __('messages.property_restored'));
        }

        return redirect()
            ->route('properties.index', ['locale' => app()->getLocale()])
            ->with('error', __('messages.property_not_found'));
    }

    public function deleteImage(PropertyImage $image): RedirectResponse
    {
        $this->authorize('update', $image->property);

        $propertyId = $image->property_id;
        $this->service->deletePropertyImage($image);

        return redirect()
            ->route('properties.edit', ['locale' => app()->getLocale(), 'property' => $propertyId])
            ->with('success', __('messages.image_deleted'));
    }

    // AJAX filter endpoint for front-end property search
    public function filter(Request $request): JsonResponse
    {
        try {
            $locale = app()->getLocale();

            $query = Property::with(['translations', 'images'])
                ->whereHas('translations', function ($q) use ($locale) {
                    $q->where('locale', $locale);
                });

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            if ($request->filled('room_count')) {
                $room_count = is_array($request->input('room_count'))
                    ? $request->input('room_count')
                    : [$request->input('room_count')];
                $query->whereIn('rooms', $room_count);
            }

            if ($request->filled('garage_count')) {
                $garage_count = is_array($request->input('garage_count'))
                    ? $request->input('garage_count')
                    : [$request->input('garage_count')];
                $query->whereIn('garages', $garage_count);
            }

            if ($request->filled('regions')) {
                $regions = is_array($request->input('regions'))
                    ? $request->input('regions')
                    : [$request->input('regions')];
                $query->whereIn('region', $regions);
            }

            $properties = $query->paginate(10);

            // Transform data to include translated names
            $transformedData = $properties->map(function ($property) use ($locale) {
                $translation = $property->getTranslation($locale);

                return [
                    'id' => $property->id,
                    'name' => $translation ? $translation->name : 'No Name',
                    'main_image' => $property->main_image,
                    'price' => $property->price,
                    'square' => $property->square,
                    'rooms' => $property->rooms,
                    'garages' => $property->garages,
                    'status' => $property->status,
                    'city' => $property->city,
                    'country' => $property->country,
                ];
            });

            return response()->json([
                'data' => $transformedData,
                'total' => $properties->total(),
                'current_page' => $properties->currentPage(),
                'per_page' => $properties->perPage(),
                'last_page' => $properties->lastPage(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Property filter error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'An error occurred while filtering properties.',
                'data' => [],
            ], 500);
        }
    }
}
