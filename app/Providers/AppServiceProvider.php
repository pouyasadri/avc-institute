<?php

namespace App\Providers;

use App\Services\SeoService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(SeoService::class, function () {
            return new SeoService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || $this->app->environment('staging')) {
            URL::forceScheme('https');
        }

        Vite::useScriptTagAttributes([
            'data-cfasync' => 'false',
        ]);

        Paginator::useBootstrapFive();

        $this->configureRateLimiting();
    }

    /**
     * Register named rate limiters for public form submissions.
     * Limits are keyed by IP address (recommended for unauthenticated public routes).
     */
    protected function configureRateLimiting(): void
    {
        // Contact form: 5 submissions per 10 minutes per IP
        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        // Consultation form: 5 submissions per 10 minutes per IP
        RateLimiter::for('consult-form', function (Request $request) {
            return Limit::perMinutes(10, 5)->by($request->ip());
        });

        // Question form: 10 submissions per 10 minutes per IP
        // (appears on university/city pages so a slightly higher limit is appropriate)
        RateLimiter::for('question-form', function (Request $request) {
            return Limit::perMinutes(10, 10)->by($request->ip());
        });

        // Blog comment form: 10 submissions per 5 minutes per IP
        RateLimiter::for('comment-form', function (Request $request) {
            return Limit::perMinutes(5, 10)->by($request->ip());
        });
    }
}
