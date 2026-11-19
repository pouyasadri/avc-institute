<?php

namespace App\Filesystem;

use Illuminate\Filesystem\AwsS3V3Adapter;

/**
 * PlanetHoster N0C public URLs.
 *
 * Objects are stored under AWS_ROOT (e.g. public/avc-institute/...).
 * The site proxy at /n0c-storage is already scoped to the bucket's public/
 * prefix, so public URLs must omit the leading "public/" segment.
 */
class N0cAwsS3V3Adapter extends AwsS3V3Adapter
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function url($path)
    {
        $path = $this->normalizeRelativePath($path);

        if (! isset($this->config['url'])) {
            return parent::url($path);
        }

        $key = ltrim($this->prefixer->prefixPath($path), '/');
        $base = rtrim($this->config['url'], '/');

        if (str_contains($base, 'n0c-storage') && str_starts_with($key, 'public/')) {
            $key = substr($key, strlen('public/'));
        }

        return $base.'/'.$key;
    }

    /**
     * Strip legacy absolute-in-bucket prefixes from DB-stored paths.
     */
    protected function normalizeRelativePath(string $path): string
    {
        $path = ltrim($path, '/');
        $root = trim((string) ($this->config['root'] ?? ''), '/');

        if ($root !== '' && str_starts_with($path, $root.'/')) {
            return substr($path, strlen($root) + 1);
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        if ($root !== '' && str_starts_with($root, 'public/')) {
            $rootWithoutPublic = substr($root, strlen('public/'));
            if ($rootWithoutPublic !== '' && str_starts_with($path, $rootWithoutPublic.'/')) {
                return substr($path, strlen($rootWithoutPublic) + 1);
            }
        }

        return $path;
    }
}
