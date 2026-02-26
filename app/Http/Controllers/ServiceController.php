<?php

namespace App\Http\Controllers;

use App\Services\StructuredData\ServiceSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display the services index page.
     */
    public function index()
    {
        $locale = app()->getLocale();
        $servicesList = __('index.services.items');
        $pageTitle = __('layout.nav.services') ?? 'Services';

        $schema = new \App\Services\StructuredData\WebPageSchema(
            url()->current(),
            $pageTitle,
            'Our services',
            $locale
        );

        return view('pages.services.index', compact('servicesList', 'locale', 'pageTitle', 'schema'));
    }

    /**
     * Display the specified service.
     */
    public function show(Request $request, string $locale, string $slug)
    {
        try {
            $locale = app()->getLocale();
            $servicesList = __('index.services.items');

            // Find the service by slug in the main services list
            $serviceIndex = collect($servicesList)->search(function ($item) use ($slug) {
                return isset($item['slug']) && $item['slug'] === $slug;
            });

            if ($serviceIndex === false) {
                return redirect()->route('index', ['locale' => $locale])
                    ->with('error', __('messages.error_not_found') ?? 'Service not found.');
            }

            // Get the specific details from the new services language file
            $serviceDetails = __('services.' . $slug);

            if (!is_array($serviceDetails) || empty($serviceDetails)) {
                Log::warning("Service details missing for slug: {$slug} in locale: {$locale}");
                return redirect()->route('index', ['locale' => $locale]);
            }

            // Generate schema data for AIO
            $schema = new ServiceSchema(
                name: $serviceDetails['title'] ?? 'Service',
                url: request()->url(),
                description: $serviceDetails['description'] ?? '',
                providerName: 'A.V.C Institute',
                areaServed: 'France'
            );

            return view('pages.services.show', compact('serviceDetails', 'slug', 'schema'));
        } catch (\Exception $e) {
            Log::error('Service show error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('index', ['locale' => app()->getLocale()])->with('error', 'An error occurred.');
        }
    }
}
