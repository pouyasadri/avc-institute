<?php

namespace App\Providers;

use App\Contracts\Calculator\StudentBudgetBenchmarkProviderInterface;
use App\Filesystem\N0cAwsS3V3Adapter;
use App\Services\Calculator\ConfigStudentBudgetBenchmarkProvider;
use App\Services\SeoService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use ReflectionMethod;

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

        $this->app->bind(
            StudentBudgetBenchmarkProviderInterface::class,
            ConfigStudentBudgetBenchmarkProvider::class
        );
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

        $this->configureN0cS3Urls();
        $this->configureRateLimiting();
    }

    /**
     * Use a custom S3 adapter so /n0c-storage public URLs omit the bucket "public/" prefix.
     */
    protected function configureN0cS3Urls(): void
    {
        Storage::extend('s3', function ($app, array $config) {
            /** @var FilesystemManager $manager */
            $manager = $app->make('filesystem');
            $original = (new ReflectionMethod($manager, 'createS3Driver'))->invoke($manager, $config);

            return new N0cAwsS3V3Adapter(
                $original->getDriver(),
                $original->getAdapter(),
                $original->getConfig(),
                $original->getClient(),
            );
        });
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

        // Cookie consent endpoint: 60 posts per minute per IP
        RateLimiter::for('gdpr-consent', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
