<?php

namespace App\Services;

use App\Enums\Locale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Location\Facades\Location;

class LocaleDetector
{
    /**
     * Detect the best locale for the user based on multiple sources.
     *
     * Priority order (configurable):
     * 1. Route parameter (explicit user choice via URL)
     * 2. Session (previously detected/chosen locale)
     * 3. Browser Accept-Language header
     * 4. IP geolocation
     * 5. Config fallback
     */
    public function detectLocale(Request $request, ?string $routeLocale = null): string
    {
        $supported = config('localization.supported_locales', Locale::values());
        $detectionMethods = [];

        // Collect all possible locales with their priorities
        $priority = config('localization.detection_priority', [
            'route' => 100,
            'session' => 90,
            'browser' => 80,
            'ip' => 70,
            'config' => 0,
        ]);

        // Route parameter (highest priority)
        if ($routeLocale && in_array($routeLocale, $supported, true)) {
            $detectionMethods[] = [
                'locale' => $routeLocale,
                'priority' => $priority['route'] ?? 100,
                'source' => 'route',
            ];
        }

        // Session
        $sessionLocale = session('locale');
        if ($sessionLocale && in_array($sessionLocale, $supported, true)) {
            $detectionMethods[] = [
                'locale' => $sessionLocale,
                'priority' => $priority['session'] ?? 90,
                'source' => 'session',
            ];
        }

        // Browser Accept-Language
        $browserLocale = $this->detectFromBrowser($request);
        if ($browserLocale && in_array($browserLocale, $supported, true)) {
            $detectionMethods[] = [
                'locale' => $browserLocale,
                'priority' => $priority['browser'] ?? 80,
                'source' => 'browser',
            ];
        }

        // IP Geolocation (if enabled)
        if (config('localization.enable_ip_detection', true)) {
            $ipLocale = $this->detectFromIp($request);
            if ($ipLocale && in_array($ipLocale, $supported, true)) {
                $detectionMethods[] = [
                    'locale' => $ipLocale,
                    'priority' => $priority['ip'] ?? 70,
                    'source' => 'ip',
                ];
            }
        }

        // Config fallback
        $detectionMethods[] = [
            'locale' => config('app.locale', 'en'),
            'priority' => $priority['config'] ?? 0,
            'source' => 'config',
        ];

        // Sort by priority (highest first) and return the best match
        usort($detectionMethods, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return $detectionMethods[0]['locale'];
    }

    /**
     * Detect locale from browser Accept-Language header with quality values.
     */
    public function detectFromBrowser(Request $request): ?string
    {
        $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');

        if (! $acceptLanguage) {
            return null;
        }

        $supported = config('localization.supported_locales', Locale::values());
        $languageMap = config('localization.language_locale_map', [
            'en' => 'en',
            'fr' => 'fr',
            'fa' => 'fa',
            'pe' => 'fa',
            'ps' => 'fa',
        ]);

        // Parse Accept-Language header with quality values
        // Example: "fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7"
        $languages = [];
        $parts = explode(',', $acceptLanguage);

        foreach ($parts as $part) {
            $part = trim($part);

            // Extract language and quality
            if (preg_match('/^([a-zA-Z]{2})(?:-[a-zA-Z]{2})?(?:;q=([0-9.]+))?$/', $part, $matches)) {
                $lang = strtolower($matches[1]);
                $quality = isset($matches[2]) ? (float) $matches[2] : 1.0;

                $languages[] = [
                    'lang' => $lang,
                    'quality' => $quality,
                ];
            }
        }

        // Sort by quality (highest first)
        usort($languages, function ($a, $b) {
            return $b['quality'] <=> $a['quality'];
        });

        // Find first supported locale
        foreach ($languages as $language) {
            $lang = $language['lang'];

            // Direct match in supported locales
            if (in_array($lang, $supported, true)) {
                return $lang;
            }

            // Check language map
            if (isset($languageMap[$lang]) && in_array($languageMap[$lang], $supported, true)) {
                return $languageMap[$lang];
            }
        }

        return null;
    }

    /**
     * Detect locale from IP geolocation.
     * Result is cached per IP for 24 hours to avoid external HTTP calls on every new session.
     */
    public function detectFromIp(Request $request): ?string
    {
        try {
            $ip = $request->ip();

            // Skip detection for local IPs
            if ($this->isLocalIp($ip)) {
                return null;
            }

            return Cache::remember("ip-locale:{$ip}", 86400, function () use ($ip) {
                $position = Location::get($ip);

                if (! $position || ! $position->countryCode) {
                    return null;
                }

                $countryCode = strtoupper($position->countryCode);
                $countryMap = config('localization.country_locale_map', []);

                return $countryMap[$countryCode] ?? null;
            });

        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::warning('IP locale detection failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Check if IP is a local/private address.
     */
    protected function isLocalIp(?string $ip): bool
    {
        if (! $ip) {
            return true;
        }

        // Check for localhost and private IP ranges
        return in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
               filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Get locale detection info for debugging.
     */
    public function getDetectionInfo(Request $request, ?string $routeLocale = null): array
    {
        return [
            'detected_locale' => $this->detectLocale($request, $routeLocale),
            'route_locale' => $routeLocale,
            'session_locale' => session('locale'),
            'browser_locale' => $this->detectFromBrowser($request),
            'ip_locale' => config('localization.enable_ip_detection', true)
                ? $this->detectFromIp($request)
                : null,
            'config_locale' => config('app.locale'),
            'ip_address' => $request->ip(),
            'accept_language' => $request->server('HTTP_ACCEPT_LANGUAGE'),
        ];
    }
}
