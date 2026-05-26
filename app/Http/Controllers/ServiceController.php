<?php

namespace App\Http\Controllers;

use App\Services\StructuredData\FAQSchema;
use App\Services\StructuredData\ServiceSchema;
use App\Services\StructuredData\WebPageSchema;
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

        $schema = new WebPageSchema(
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
            $serviceDetails = __('services.'.$slug);

            if (! is_array($serviceDetails) || empty($serviceDetails)) {
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

            // Generate FAQ schema if the service has FAQ items
            // This unlocks Google's FAQ rich results (expandable Q&As in SERPs)
            $faqSchema = null;
            if (! empty($serviceDetails['faq']) && is_array($serviceDetails['faq'])) {
                $faqSchema = new FAQSchema;
                foreach ($serviceDetails['faq'] as $faq) {
                    if (! empty($faq['q']) && ! empty($faq['a'])) {
                        $faqSchema->addQuestion($faq['q'], $faq['a']);
                    }
                }
            }

            return view('pages.services.show', compact('serviceDetails', 'slug', 'schema', 'faqSchema'));
        } catch (\Exception $e) {
            Log::error('Service show error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->route('index', ['locale' => app()->getLocale()])->with('error', 'An error occurred.');
        }
    }
}
