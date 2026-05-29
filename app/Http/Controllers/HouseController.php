<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseRequest;
use App\Models\House;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HouseController extends Controller
{
    public function index(): View
    {
        $houses = House::orderBy('created_at', 'desc')->paginate(10);
        $maxPrice = House::max('house_price');
        $maxRoomCount = House::max('house_room_count');
        $maxGarageCount = House::max('house_garage_count');
        $regions = House::distinct('house_region')->pluck('house_region');

        return view('house.index', compact(['houses', 'maxPrice', 'maxRoomCount', 'maxGarageCount', 'regions']));
    }

    public function show(House $house): View
    {
        $similar_houses_query = House::where('id', '!=', $house->id)->where('house_region', $house->house_region)->get();
        $similar_houses_count = $similar_houses_query->count();
        $similar_houses = $similar_houses_query->random($similar_houses_count < 3 ? $similar_houses_count : 3);
        $nextHouse = House::where('id', '>', $house->id)->orderBy('id', 'asc')->first();
        $prevHouse = House::where('id', '<', $house->id)->orderBy('id', 'desc')->first();

        return view('house.show', compact('house', 'nextHouse', 'prevHouse', 'similar_houses'));
    }

    public function create(): View
    {
        $houses = House::all();

        return view('house.admin', compact('houses'));
    }

    public function store(StoreHouseRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($request, $validatedData) {
            $house_main_image_path = $request->file('house_main_image')->storeAs('public/images/houses', Str::random(40).'.'.$request->file('house_main_image')->extension());
            $house_main_image = basename($house_main_image_path);

            $house_images = [];
            $house_images[] = $house_main_image;
            if ($request->hasFile('house_images')) {
                foreach ($request->file('house_images') as $image) {
                    if ($image->isValid()) {
                        $name = Str::random(40).'.'.$image->extension();
                        $image->storeAs('public/images/houses', $name);
                        $house_images[] = $name;
                    }
                }
            }

            $house = new House(array_merge($validatedData, [
                'house_main_image' => $house_main_image,
                'house_status' => 'در دسترس',
                'house_images' => json_encode($house_images),
            ]));
            $house->save();
        });

        return redirect('/home')->with('success', 'House saved');
    }

    public function filter(Request $request): JsonResponse
    {
        $query = House::query();

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('house_price', [$request->min_price, $request->max_price]);
        }

        if ($request->filled('room_count')) {
            $room_count = is_array($request->room_count) ? $request->room_count : [$request->room_count];
            $query->whereIn('house_room_count', $room_count);
        }

        if ($request->filled('garage_count')) {
            $garage_count = is_array($request->garage_count) ? $request->garage_count : [$request->garage_count];
            $query->whereIn('house_garage_count', $garage_count);
        }

        if ($request->filled('regions')) {
            $regions = is_array($request->regions) ? $request->regions : [$request->regions];
            $query->whereIn('house_region', $regions);
        }

        $houses = $query->paginate(10);

        return response()->json($houses);

    }

    public function destroy(House $house): RedirectResponse
    {
        Storage::delete($house->house_main_image);
        foreach ($house->house_images as $image) {
            Storage::delete('public/images/houses/'.$image);
        }

        $house->delete();

        return redirect('/home')->with('success', 'House deleted successfully');
    }
}
