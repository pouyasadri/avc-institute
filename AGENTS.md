# AGENTS.md — Coding Agent Instructions

This file provides authoritative guidance for agentic coding tools operating in this repository.

Repository: `avc-institute` (git@github.com:pouyasadri/avc-institute.git)

---

## Project Overview

**A.V.C Institute** — a multilingual Laravel 12.x web application for an immigration/education/real-estate consulting firm. The stack is PHP 8.4+, Blade templates, Bootstrap 5, and Vite. There is no TypeScript, React, or Vue.

Supported locales: `en`, `fr`, `fa` (Persian/RTL). All public routes are locale-prefixed (`/{locale}/...`).

---

## Package Managers

| Layer | Manager | Lock file |
|-------|---------|-----------|
| PHP | Composer 2 | `composer.lock` |
| JS/CSS | npm | `package-lock.json` |

Never use `yarn` or `pnpm`. Always use `npm ci` (not `npm install`) in automated contexts.

---

## Commands

### Development

```bash
php artisan serve          # Start PHP dev server
npm run dev                # Start Vite dev server with HMR
docker compose up -d       # Start MySQL 8 + phpMyAdmin containers
```

### Build

```bash
npm run build              # Compile and fingerprint assets (Vite)
composer install --no-dev --optimize-autoloader   # Production PHP deps
php artisan optimize       # Cache config, routes, and views
php artisan storage:link   # Create public/storage symlink (first deploy)
```

### Testing

```bash
php artisan test                                      # Run all tests
./vendor/bin/phpunit                                  # Run all tests (direct)

# Run a single test class
php artisan test --filter LocaleDetectorTest

# Run a single test method
php artisan test --filter "test_detects_locale_from_browser_accept_language"

# Run a single file
./vendor/bin/phpunit tests/Unit/LocaleDetectorTest.php

# Run a specific suite only
./vendor/bin/phpunit --testsuite Unit
./vendor/bin/phpunit --testsuite Feature
```

Tests use SQLite in-memory (`DB_DATABASE=:memory:`), array cache/session/queue, and `BCRYPT_ROUNDS=4`. No database setup is required to run tests.

### Code Formatting

```bash
./vendor/bin/pint           # Format all PHP files (Laravel Pint, PSR-12 defaults)
./vendor/bin/pint app/      # Format a specific directory
./vendor/bin/pint app/Services/BlogService.php   # Format a single file
```

There is no JS/CSS linter configured. Pint has no custom `pint.json` — it uses Laravel defaults.

---

## Directory Structure

```
app/
  Exceptions/       Global exception handler (minimal, inherits Laravel defaults)
  Helpers/          BreadcrumbHelper, ImageSeoHelper (static utility classes)
  Http/
    Controllers/    Admin/, Api/, Auth/, plus top-level public controllers
    Middleware/     13 middlewares (SetLocale, AdminMiddleware, CheckRedirects…)
    Requests/       11 FormRequest classes (validation rules + messages)
  Mail/             6 Mailable classes for confirmation emails
  Models/           13 Eloquent models (soft deletes, ULID PKs on key models)
  Policies/         3 authorization policies (Blog, BlogCategory, Property)
  Services/         Domain services + StructuredData/ (13 Schema.org builders)
config/             19 config files; custom: localization.php, seo.php, purifier.php
database/           migrations/, factories/, seeders/
resources/
  js/               app.js (entry point, imports bootstrap.js only)
  sass/             app.scss, _variables.scss (Bootstrap overrides)
  views/            Blade templates (admin/, blog/, city/, pages/, properties/, university/)
  lang/             Translation files (en/, fr/, fa/)
routes/             web.php (locale-prefixed + admin), api.php
tests/
  Feature/          HTTP/integration tests (RedirectsTest, SeoCanonicalTest)
  Unit/             Unit tests (LocaleDetectorTest)
```

---

## Code Style

### PHP General

- Follow **PSR-12** and **Laravel conventions** (enforced by Pint).
- Indentation: **4 spaces** (never tabs). Final newline required. LF line endings.
- Opening brace on the **same line** for classes and methods (K&R style).
- Always declare **return types** on public methods: `View`, `RedirectResponse`, `string`, `bool`, `void`, `?string`, `array`, `mixed`.
- Use **constructor property promotion** for new classes (used in `IndexController`).
- Use trailing commas in multi-line arrays.

### Imports / `use` Statements

Order `use` statements in three groups separated by a blank line:

1. Framework/package classes (`Illuminate\...`, `Laravel\...`, third-party)
2. App classes (`App\Models\...`, `App\Services\...`, `App\Http\...`)
3. PHP built-ins / interfaces (`Closure`, `Throwable`, `Carbon\Carbon`)

Do **not** use inline fully-qualified names (e.g., `\Log::error(...)`) — always add a `use` statement at the top instead. This inconsistency exists in older controllers; do not perpetuate it.

### Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Classes | `PascalCase` | `PropertyService`, `LocaleDetector` |
| Methods | `camelCase` | `storeProperty()`, `detectFromBrowser()` |
| Local variables | `camelCase` | `$mainImagePath`, `$includeTrashed` |
| DB columns / form input | `snake_case` | `from_url`, `is_admin` |
| Route names | `dot.notation` | `blog.index`, `admin.blog.index` |
| View paths | `dot.notation` | `'blog.show'`, `'admin.blog.create'` |
| DB tables | `snake_case` plural | `blog_posts`, `property_images` |
| Models | `PascalCase` singular | `Blog`, `PropertyImage` |
| Controllers | `PascalCase` + `Controller` | `PropertyController` |
| Services | `PascalCase` + `Service` | `BlogService`, `SeoService` |
| FormRequests | `PascalCase` + `Request` | `StorePropertyRequest` |
| Config keys | `snake_case` | `supported_locales`, `detection_priority` |

### Blade / Views

- Pass data with `compact()`: `return view('blog.show', compact('post', 'seo'))`.
- Use `__('messages.key')` or `__('index.meta.title')` for all user-facing strings.
- Locale-specific logic lives in `app/Services/LocaleDetector.php` and `config/localization.php`.

---

## Error Handling

Four established patterns — match the context:

**1. Controller actions — try/catch with log + graceful fallback**
```php
try {
    // main logic
} catch (\Exception $e) {
    \Log::error('Context error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    return view('properties.index', $fallbackData)->with('error', __('messages.error'));
}
// For JSON endpoints:
return response()->json(['error' => true, 'message' => '...', 'data' => []], 500);
```

**2. Services — try/catch with warning + null return (non-fatal degradation)**
```php
try {
    // external call (e.g., IP geolocation)
} catch (\Exception $e) {
    \Log::warning('Feature failed: ' . $e->getMessage());
    return null;
}
```

**3. Middleware — silent catch for truly non-fatal side effects**
```php
try {
    Carbon::setLocale($locale);
} catch (\Throwable $e) {
    // non-fatal, ignore
}
```

**4. Write operations — always wrap in `DB::transaction()`**
```php
return DB::transaction(function () use ($data) {
    // All Eloquent creates/updates/deletes here
    // Exceptions bubble up naturally and roll back the transaction
});
```

Use `FormRequest` classes for all input validation — never validate manually in controllers. Use `$this->authorize('action', $model)` via Policies for authorization. When a resource is not found, redirect with a flash error rather than calling `abort(404)`.

---

## Models

- Primary keys on key models use **ULIDs** (not integer auto-increment).
- Use **soft deletes** (`SoftDeletes` trait) on content models (`Blog`, `Property`, etc.).
- Translatable content is handled via separate `*Translation` models (e.g., `BlogPostTranslation`).
- Eager-load relationships to avoid N+1 — use `with()` in service layer, not controllers.

---

## Testing Conventions

- Test classes extend `Tests\TestCase`.
- Feature tests may use `RefreshDatabase` trait (SQLite in-memory, fast).
- Test method names use the `test_snake_case_description` format.
- Do not test implementation details — test observable behavior (HTTP responses, DB state, returned values).
- Factory usage: `ModelName::factory()->create([...])`.

---

## CI/CD Notes

The GitHub Actions workflow (`.github/workflows/deploy.yml`) triggers on push to `main` and runs:
1. `composer install --no-dev --optimize-autoloader`
2. `npm ci && npm run build`
3. `rsync` to production server (PlanetHoster) over SSH
4. Remote: `php artisan migrate --force`, `db:seed --force`, `optimize`, `view:cache`

There are **no automated tests in CI**. Run `php artisan test` locally before merging to `main`.
