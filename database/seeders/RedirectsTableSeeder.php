<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Redirect;
use Illuminate\Database\Seeder;

class RedirectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the legacy domains and their corresponding new locale prefixes
        $domains = [
            'en.applyvipconseil.com' => 'en',
            'fr.applyvipconseil.com' => 'fr',
            'applyvipconseil.com' => 'fa', // The main domain was previously the FA version
        ];

        // Define the static paths to redirect
        // These are paths that existed on the old versions and map 1:1 to the new structure
        $staticPaths = [
            '/' => '/',
            '/consult' => '/consult',
            '/contactUs' => '/contactUs',
            '/blog' => '/blog',
            '/cities' => '/cities',
            '/universities' => '/universities',
        ];

        // Dynamic resources (Cities)
        $cities = ['paris', 'strasbourg', 'nice', 'toulouse', 'lyon', 'grenoble'];

        // Dynamic resources (Universities)
        $universities = [
            'paris-saclay-university',
            'sorbonne-paris-nord',
            'paris-cite',
            'paris-4-sorbonne',
            'paris-3',
            'paris-2',
            'lyon-3',
            'lyon-2',
            'lyon-1',
            'pantheon-sorbonne',
            'cote-d-azure',
            'toulouse',
            'strasbourg',
        ];

        foreach ($domains as $oldDomain => $newLocale) {
            // 1. Static Paths
            foreach ($staticPaths as $oldPath => $newPath) {
                $this->createRedirect($oldDomain, $oldPath, $newLocale, $newPath);
            }

            // 2. Cities
            foreach ($cities as $city) {
                $this->createRedirect($oldDomain, "/cities/{$city}", $newLocale, "/cities/{$city}");
            }

            // 3. Universities
            foreach ($universities as $university) {
                $this->createRedirect($oldDomain, "/universities/{$university}", $newLocale, "/universities/{$university}");
            }
        }

        // 4. Blog Posts
        // We assume the old versions might have used Slugs OR IDs.
        // We redirect both to the new ID-based URL (as per current route definition).
        // If the old app used slugs: old_domain/blog/{slug} -> new_domain/en/blog/{id}
        // If the old app used IDs:   old_domain/blog/{id}   -> new_domain/en/blog/{id}

        $blogs = Blog::with('translations')->get();

        foreach ($blogs as $blog) {
            foreach ($domains as $oldDomain => $newLocale) {
                // Determine the target URL for this blog and locale
                // Route: /{locale}/blog/{blog} (ID based)
                $toUrl = "https://applyvipconseil.com/{$newLocale}/blog/{$blog->id}";

                // Redirect ID based URL
                $fromIdUrl = "https://{$oldDomain}/blog/{$blog->id}";
                Redirect::firstOrCreate(
                    ['from_url' => $fromIdUrl],
                    ['to_url' => $toUrl, 'http_code' => 301]
                );

                // Redirect Slug based URL
                // We try to find the slug for this locale.
                // If the old domain was 'en.applyvipconseil.com', we look for English slug.
                $translation = $blog->translations->where('locale', $newLocale)->first();
                if ($translation && ! empty($translation->slug)) {
                    $fromSlugUrl = "https://{$oldDomain}/blog/{$translation->slug}";
                    Redirect::firstOrCreate(
                        ['from_url' => $fromSlugUrl],
                        ['to_url' => $toUrl, 'http_code' => 301]
                    );
                }
            }
        }
    }

    /**
     * Helper to create a redirect record.
     */
    private function createRedirect(string $oldDomain, string $oldPath, string $newLocale, string $newPath): void
    {
        // Construct the full 'from' URL.
        // We handle both http and https in a real scenario, but for the database we might just store
        // the host + path or a standard normalized version.
        // Based on the user request, they want to "add the all the url pages".
        // I will store the full URL with https scheme as the primary key relies on uniqueness
        // and usually we want to match exact incoming URLs.
        // However, middleware often checks path or host+path.
        // Let's store "host/path" (without scheme) or "https://host/path".
        // Given modern web, let's assume HTTPS.

        // Clean up paths to ensure single leading slash for path part,
        // but not for the final URL construction.
        $oldPath = '/'.ltrim($oldPath, '/');
        $newPath = '/'.ltrim($newPath, '/');

        // Case 1: Root path special handling if needed (already covered by ltrim/slash logic)
        // en.applyvipconseil.com/ -> applyvipconseil.com/en

        // Construct from_url
        $fromUrl = "https://{$oldDomain}{$oldPath}";

        // Remove trailing slash if it's not root, or keep it consistent.
        // Let's normalize by removing trailing slash unless it's just root.
        if ($oldPath !== '/' && str_ends_with($fromUrl, '/')) {
            $fromUrl = rtrim($fromUrl, '/');
        }

        // Construct to_url
        // The new structure is applyvipconseil.com/{locale}{path}
        // Exception: if newPath is '/', result is applyvipconseil.com/{locale}
        if ($newPath === '/') {
            $toUrl = "https://applyvipconseil.com/{$newLocale}";
        } else {
            $toUrl = "https://applyvipconseil.com/{$newLocale}{$newPath}";
        }

        // Check uniqueness before creating to avoid duplicate key errors if run multiple times
        // or if overlap exists.
        // Using updateOrCreate or just ignoring existing.
        // Since it's a seeder, create or ignore is best.

        Redirect::firstOrCreate(
            ['from_url' => $fromUrl],
            [
                'to_url' => $toUrl,
                'http_code' => 301,
            ]
        );
    }
}
