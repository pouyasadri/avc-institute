<?php

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use App\Services\LocaleDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to a stable default locale for SEO-friendly canonical URLs
Route::get('/', function () {
    $defaultLocale = config('seo.default_locale', 'fa');

    return redirect("/{$defaultLocale}", 301);
})->name('root.redirect');

// Keep sitemap at root (not locale-specific)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Locale-prefixed routes
Route::prefix('{locale}')
    ->whereIn('locale', ['en', 'fr', 'fa'])
    ->middleware(SetLocale::class)
    ->group(function () {
        // Home Route (localized)
        Route::get('/', [IndexController::class, 'index'])->name('index');

        // Cities Routes
        Route::prefix('cities')->group(function () {
            Route::view('/', 'pages.cities.index')->name('cities.index');
            $cities = ['paris', 'strasbourg', 'nice', 'toulouse', 'lyon'];
            foreach ($cities as $city) {
                Route::view("/$city", "city.$city")->name("cities.$city");
            }
        });

        // Universities Routes
        Route::prefix('universities')->group(function () {
            Route::view('/', 'pages.universities.index')->name('universities.index');
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
                'universite-psl',
                'ip-paris',
                'universite-grenoble-alpes',
                'aix-marseille-university',
            ];
            foreach ($universities as $university) {
                Route::view("/$university", "university.$university")->name("universities.$university");
            }
        });
        // Blog Routes
        Route::prefix('blog')->group(function () {
            Route::get('/', [BlogController::class, 'index'])->name('blog.index');
            Route::get('/create', [BlogController::class, 'create'])->middleware('auth')->name('blog.create');
            Route::post('/', [BlogController::class, 'store'])->middleware('auth')->name('blog.store');
            Route::post('/{id}/restore', [BlogController::class, 'restore'])->middleware('auth')->name('blog.restore');
            Route::get('/{blog}', [BlogController::class, 'show'])->name('blog.show');
            Route::get('/{blog}/edit', [BlogController::class, 'edit'])->middleware('auth')->name('blog.edit');
            Route::put('/{blog}', [BlogController::class, 'update'])->middleware('auth')->name('blog.update');
            Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('blog.delete')->middleware('auth');

            // Comments
            Route::post('/{blog}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

            // Category CRUD
            Route::prefix('/categories')->group(function () {
                Route::get('/', [BlogCategoryController::class, 'index'])->name('blog.categories.index');
                Route::get('/create', [BlogCategoryController::class, 'create'])->middleware('auth')->name('blog.categories.create');
                Route::post('/', [BlogCategoryController::class, 'store'])->middleware('auth')->name('blog.categories.store');
                Route::get('/{category}/edit', [BlogCategoryController::class, 'edit'])->middleware('auth')->name('blog.categories.edit');
                Route::put('/{category}', [BlogCategoryController::class, 'update'])->middleware('auth')->name('blog.categories.update');
                Route::delete('/{category}', [BlogCategoryController::class, 'destroy'])->middleware('auth')->name('blog.categories.delete');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | PROPERTIES FEATURE DISABLED - COMING SOON
        |--------------------------------------------------------------------------
        | Property routes temporarily disabled. To re-enable:
        | 1. Uncomment this section (lines 78-99)
        | 2. Remove temporary "coming soon" routes below
        | 3. Clear route cache: php artisan route:clear
        | 4. See PROPERTIES_DISABLED.md for full restoration guide
        |--------------------------------------------------------------------------

        // Properties Routes
        Route::prefix('properties')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
            Route::get('/create', [PropertyController::class, 'create'])->middleware('auth')->name('properties.create');
            Route::post('/', [PropertyController::class, 'store'])->middleware('auth')->name('properties.store');
            Route::post('/{id}/restore', [PropertyController::class, 'restore'])->middleware('auth')->name('properties.restore');
            Route::delete('/images/{image}', [PropertyController::class, 'deleteImage'])->middleware('auth')->name('properties.images.delete');
            Route::get('/{property}', [PropertyController::class, 'show'])->name('properties.show');
            Route::get('/{property}/edit', [PropertyController::class, 'edit'])->middleware('auth')->name('properties.edit');
            Route::put('/{property}', [PropertyController::class, 'update'])->middleware('auth')->name('properties.update');
            Route::delete('/{property}', [PropertyController::class, 'destroy'])->middleware('auth')->name('properties.destroy');
        });

        // Property front-end Routes (was property)
        Route::prefix('property')->group(function () {
            Route::get('/admin', [PropertyController::class, 'create'])->middleware('auth');
            Route::post('/admin', [PropertyController::class, 'store'])->middleware('auth');
            Route::get('/{property}/delete', [PropertyController::class, 'destroy'])->name('property.delete');
            Route::get('/', [PropertyController::class, 'index'])->name('property.index');
            Route::get('/{property}', [PropertyController::class, 'show'])->name('property.show');
        });
        Route::get('/property/filter', [PropertyController::class, 'filter'])->name('property.filter');

        |--------------------------------------------------------------------------
        | END PROPERTIES FEATURE DISABLED
        |--------------------------------------------------------------------------
        */

        // TEMPORARY: Show "Coming Soon" page for all property URLs
        $propertiesComingSoon = function () {
            return view('properties.coming-soon');
        };

        // Keep route names compatible with the (disabled) feature so links/forms don't error.
        Route::prefix('properties')->group(function () use ($propertiesComingSoon) {
            Route::get('/', $propertiesComingSoon)->name('properties.index');
            Route::get('/create', $propertiesComingSoon)->name('properties.create');
            Route::get('/{id}/edit', $propertiesComingSoon)->name('properties.edit');
            Route::get('/{id}', $propertiesComingSoon)->name('properties.show');

            // Mutating routes: redirect back to the index (which shows Coming Soon)
            Route::post('/', function (Request $request) {
                return redirect()->route('properties.index', ['locale' => $request->route('locale')]);
            })->name('properties.store');
            Route::put('/{id}', function (Request $request) {
                return redirect()->route('properties.index', ['locale' => $request->route('locale')]);
            })->name('properties.update');
            Route::delete('/{id}', function (Request $request) {
                return redirect()->route('properties.index', ['locale' => $request->route('locale')]);
            })->name('properties.destroy');
            Route::delete('/images/{image}', function (Request $request) {
                return redirect()->route('properties.index', ['locale' => $request->route('locale')]);
            })->name('properties.images.delete');
            Route::post('/{id}/restore', function (Request $request) {
                return redirect()->route('properties.index', ['locale' => $request->route('locale')]);
            })->name('properties.restore');
        });

        Route::prefix('property')->group(function () use ($propertiesComingSoon) {
            Route::get('/', $propertiesComingSoon)->name('property.index');
            Route::get('/admin', $propertiesComingSoon);
            Route::post('/admin', function (Request $request) {
                return redirect()->route('property.index', ['locale' => $request->route('locale')]);
            });
            Route::get('/filter', $propertiesComingSoon)->name('property.filter');
            Route::get('/{id}/delete', $propertiesComingSoon)->name('property.delete');
            Route::get('/{id}', $propertiesComingSoon)->name('property.show');
        });

        // Service Routes
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

        // Other Routes
        Route::view('/consult', 'pages.consult')->name('consult');
        Route::post('/consult/submit', [ConsultController::class, 'submit'])->name('consult.submit');

        Route::view('/contactUs', 'pages.contact')->name('contact');
        Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

        Route::post('/questions/submit', [QuestionController::class, 'submit'])->name('questions.submit');

        // Auth routes localized
    
        // Debug route for locale detection (only in non-production)
        if (app()->environment('local', 'development')) {
            Route::get('/locale-debug', function (Request $request) {
                $localeDetector = app(LocaleDetector::class);
                $info = $localeDetector->getDetectionInfo($request, $request->route('locale'));

                return response()->json($info);
            })->name('locale.debug');
        }
    });

// Auth Routes (Non-Localized, defaults to FR often preferred for admin, or just default app locale)
// User requested "just for the admin section", and "it should be in fr" for login.
// We can force FR locale for these routes.
Route::middleware(['admin.locale'])->group(function () {
    Auth::routes(['register' => false, 'reset' => false]);

    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Blog Routes
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class)->names('blog');
        Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class)->only(['index', 'show', 'destroy']);
        Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->only(['index', 'update', 'destroy']);
        Route::resource('consulting', \App\Http\Controllers\Admin\ConsultingController::class)->only(['index', 'show', 'destroy']);
        Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class)->only(['index', 'create', 'store', 'destroy']);
    });
});

// Redirects for legacy/locale-prefixed admin/login routes
Route::get('/{locale}/login', function () {
    return redirect('/login');
})->whereIn('locale', ['en', 'fr', 'fa']);

Route::get('/{locale}/admin', function () {
    return redirect('/admin');
})->whereIn('locale', ['en', 'fr', 'fa']);

Route::get('/{locale}/admin/{any?}', function ($locale, $any = null) {
    return redirect('/admin/' . $any);
})->whereIn('locale', ['en', 'fr', 'fa'])->where('any', '.*');
