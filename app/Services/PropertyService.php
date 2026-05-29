<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PropertyService
{
    protected ?ImageManager $imageManager = null;

    /**
     * Lazy-instantiate ImageManager only when needed for uploads.
     * This avoids the overhead on read-only requests.
     */
    protected function imageManager(): ImageManager
    {
        if ($this->imageManager === null) {
            $this->imageManager = new ImageManager(new Driver);
        }

        return $this->imageManager;
    }

    public function getAllProperties(bool $includeTrashed = false): Collection
    {
        $query = Property::with(['translations', 'images'])
            ->orderBy('created_at', 'desc');

        if ($includeTrashed) {
            $query->withTrashed();
        }

        return $query->get();
    }

    public function getPublishedProperties(): Collection
    {
        return Property::published()
            ->with(['translations', 'images'])
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function getPropertyBySlug(string $locale, string $slug): ?Property
    {
        return Property::whereHas('translations', function ($q) use ($locale, $slug) {
            $q->where('locale', $locale)->where('slug', $slug);
        })->with(['translations', 'images'])->first();
    }

    public function storeProperty(array $data): Property
    {
        return DB::transaction(function () use ($data) {
            // Handle main image upload
            $mainImagePath = null;
            if (! empty($data['main_image'])) {
                $mainImagePath = $this->uploadImage($data['main_image'], config('app.property_image_path'));
            }

            // Create property
            $property = Property::create([
                'price' => $data['price'],
                'rooms' => $data['rooms'] ?? 0,
                'garages' => $data['garages'] ?? 0,
                'type' => $data['type'],
                'status' => $data['status'] ?? 'available',
                'address_line' => $data['address_line'] ?? null,
                'postal_code' => $data['postal_code'],
                'city' => $data['city'],
                'region' => $data['region'] ?? null,
                'country' => $data['country'] ?? 'FR',
                'main_image' => $mainImagePath,
                'published_at' => $data['published_at'] ?? null,
            ]);

            // Create translations
            if (! empty($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    if (! empty($translation['locale'])) {
                        $slug = $translation['slug'] ?? $this->generateUniqueSlug(
                            $translation['name'] ?? '',
                            $translation['locale'],
                            $property->id
                        );

                        $property->translations()->create([
                            'locale' => $translation['locale'],
                            'name' => $translation['name'] ?? '',
                            'slug' => $slug,
                            'description' => clean($translation['description'] ?? ''),
                        ]);
                    }
                }
            }

            // Handle gallery images
            if (! empty($data['gallery_images']) && is_array($data['gallery_images'])) {
                foreach ($data['gallery_images'] as $index => $image) {
                    $imagePath = $this->uploadImage($image, config('app.property_gallery_path'));
                    $property->images()->create([
                        'path' => $imagePath,
                        'is_primary' => false,
                        'position' => $index,
                    ]);
                }
            }

            return $property->load(['translations', 'images']);
        });
    }

    public function updateProperty(Property $property, array $data): Property
    {
        return DB::transaction(function () use ($property, $data) {
            // Handle main image replacement
            if (! empty($data['main_image'])) {
                if ($property->main_image) {
                    Storage::delete($property->main_image);
                }
                $data['main_image'] = $this->uploadImage($data['main_image'], config('app.property_image_path'));
            } else {
                unset($data['main_image']);
            }

            // Update property
            $property->update([
                'price' => $data['price'] ?? $property->price,
                'rooms' => $data['rooms'] ?? $property->rooms,
                'garages' => $data['garages'] ?? $property->garages,
                'type' => $data['type'] ?? $property->type,
                'status' => $data['status'] ?? $property->status,
                'address_line' => $data['address_line'] ?? $property->address_line,
                'postal_code' => $data['postal_code'] ?? $property->postal_code,
                'city' => $data['city'] ?? $property->city,
                'region' => $data['region'] ?? $property->region,
                'country' => $data['country'] ?? $property->country,
                'main_image' => $data['main_image'] ?? $property->main_image,
                'published_at' => $data['published_at'] ?? $property->published_at,
            ]);

            // Update translations
            if (! empty($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    if (! empty($translation['locale'])) {
                        $existingTranslation = $property->translations()
                            ->where('locale', $translation['locale'])
                            ->first();

                        $slug = $translation['slug'] ?? $this->generateUniqueSlug(
                            $translation['name'] ?? '',
                            $translation['locale'],
                            $property->id
                        );

                        if ($existingTranslation) {
                            $existingTranslation->update([
                                'name' => $translation['name'] ?? $existingTranslation->name,
                                'slug' => $slug,
                                'description' => clean($translation['description'] ?? $existingTranslation->description),
                            ]);
                        } else {
                            $property->translations()->create([
                                'locale' => $translation['locale'],
                                'name' => $translation['name'] ?? '',
                                'slug' => $slug,
                                'description' => clean($translation['description'] ?? ''),
                            ]);
                        }
                    }
                }
            }

            // Handle new gallery images
            if (! empty($data['gallery_images']) && is_array($data['gallery_images'])) {
                $currentMaxPosition = $property->images()->max('position') ?? 0;
                foreach ($data['gallery_images'] as $index => $image) {
                    $imagePath = $this->uploadImage($image, config('app.property_gallery_path'));
                    $property->images()->create([
                        'path' => $imagePath,
                        'is_primary' => false,
                        'position' => $currentMaxPosition + $index + 1,
                    ]);
                }
            }

            return $property->refresh()->load(['translations', 'images']);
        });
    }

    public function deleteProperty(Property $property): void
    {
        DB::transaction(function () use ($property) {
            // Delete main image
            if ($property->main_image) {
                Storage::delete($property->main_image);
            }

            // Delete gallery images
            foreach ($property->images as $image) {
                Storage::delete($image->path);
                $image->delete();
            }

            $property->delete();
        });
    }

    public function restoreProperty(string $id): ?Property
    {
        /** @var Property|null $property */
        $property = Property::withTrashed()->find($id);
        if ($property) {
            $property->restore();
            $property->load(['translations', 'images']);

            return $property;
        }

        return null;
    }

    public function deletePropertyImage(PropertyImage $image): void
    {
        Storage::delete($image->path);
        $image->delete();
    }

    protected function uploadImage($file, string $path): string
    {
        // Generate unique filename with webp extension
        $filename = time().'_'.Str::random(10).'.webp';
        $fullPath = $path.$filename;

        // Process and optimize image
        $image = $this->imageManager()->read($file);

        // Resize if larger than max width
        if ($image->width() > 1920) {
            $image->scale(width: 1920);
        }

        // Encode with quality
        $encoded = $image->toWebp(quality: 80);

        // Store the image
        Storage::put($fullPath, $encoded);

        return $fullPath;
    }

    protected function generateUniqueSlug(string $name, string $locale, ?string $excludeId = null): string
    {
        $slug = Str::slug($name);
        if (empty($slug)) {
            $slug = Str::random(8);
        }
        $originalSlug = $slug;

        // Fetch all existing slugs that start with the base slug in one query
        $query = Property::whereHas('translations', function ($q) use ($originalSlug, $locale) {
            $q->where('locale', $locale)->where('slug', 'like', $originalSlug.'%');
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingSlugs = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale)->select('slug');
        }])->get()->flatMap(fn ($p) => $p->translations->pluck('slug'))->all();

        $counter = 1;
        while (in_array($slug, $existingSlugs, true)) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, string $locale, ?string $excludeId = null): bool
    {
        $query = Property::whereHas('translations', function ($q) use ($slug, $locale) {
            $q->where('locale', $locale)->where('slug', $slug);
        });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
