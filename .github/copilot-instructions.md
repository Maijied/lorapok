# Copilot instructions — Lorapok (Laravel Execution Monitor)

Quick orientation for AI coding agents working in this repo.

Big picture
- Repository contains a Laravel package (Lorapok/ExecutionMonitor) plus a local example app at `laravel-test-app/laravel` used for development and QA.
- Core components:
  - Service Provider(s): `ExecutionMonitorServiceProvider.php` (root and package copies) — registers singleton, config, views, routes, middleware and console commands.
  - Runtime: `Monitor.php` — timers, queries, routes, report generation.
  - Helpers: `src/helpers.php` / `helpers.php` — `monitor()` and `execution_monitor_enabled()` provide safe no-op fallbacks.
  - Config: `config/execution-monitor.php` (see `laravel-test-app/laravel/config/execution-monitor.php` for the active dev config).
  - Views & UI: `resources/views` (widget lives in package and in `laravel-test-app` copy for dev).
  - Middleware: `InjectMonitorWidget` and `TrackExecutionTime` — middleware is pushed to the HTTP kernel (global injection).

Key repo patterns and gotchas
- Two copies of the package exist: a distributable package under `lorapok-laravel-execution-monitor-v1.0.0/lorapok-package/src` and a local-development copy under `laravel-test-app/laravel/packages/lorapok/src`. Edits for packaging should generally be made in the `lorapok-package/src` copy, but local dev iterations are typically done in the `laravel-test-app` copy.
- Auto-enable logic: `ExecutionMonitorServiceProvider` uses `config('execution-monitor.auto_detect')` and `allowed_environments` to decide whether to enable monitoring. Some local-provider variants load views/routes regardless of enabled state — be mindful when syncing behavior.
- DB query tracking: registered via `DB::listen()`; some providers register the listener early using `$this->app->booted()` — ensure the DB connection is available when adding listeners.
- Helper semantics: `monitor()` returns a no-op object when the monitor service isn't bound — safe to call but be aware tests may expect a real `Monitor` instance in the local app.
- Middleware is added with `$kernel->pushMiddleware(...)` (global), not via route groups.

Developer workflows (project-specific)
- Install package for real projects: `composer require lorapok/laravel-execution-monitor`.
- Publish config/views when testing manually: `php artisan vendor:publish --tag=lorapok-config` and `--tag=lorapok-views`.
- Runtime artisan utilities: `php artisan monitor:install`, `php artisan monitor:enable`, `php artisan monitor:disable`, `php artisan monitor:status`.
- Local dev (inside the repository's `laravel-test-app` Docker/container environment): clear views/cache and restart services after changes. Example commands used by maintainers:
```
docker exec lorapok-test-app php /var/www/html/artisan view:clear
docker exec lorapok-test-app php /var/www/html/artisan cache:clear
docker compose restart
```
- When editing view/templates: the active dev widget is at `laravel-test-app/laravel/packages/lorapok/src/resources/views/widget.blade.php`.

Code patterns & examples (copy-paste safe)
- Start/end timers (facade or helper):
```
use Lorapok\ExecutionMonitor\Facades\Monitor;
Monitor::start('expensive-operation');
// ... do work
Monitor::end('expensive-operation');

// or via helper
monitor('api-call', fn() => Http::get('https://api.example.com'));
```
- Add a DB listener (use booted callback when needed):
```
$this->app->booted(function () {
    DB::listen(function ($query) {
        if ($this->app->bound('execution-monitor')) {
            app('execution-monitor')->logQuery($query->sql, $query->time);
        }
    });
});
```

Where to look first (high value files)
- Root provider: `ExecutionMonitorServiceProvider.php`
- Packaged provider: `lorapok-laravel-execution-monitor-v1.0.0/lorapok-package/src/ExecutionMonitorServiceProvider.php`
- Monitor implementation: `Monitor.php` (root and package copies)
- Helpers: `lorapok-laravel-execution-monitor-v1.0.0/lorapok-package/src/helpers.php` and `laravel-test-app/laravel/packages/lorapok/src/helpers.php`
- Dev app config: `laravel-test-app/laravel/config/execution-monitor.php`
- Widget view (edit here when iterating UI): `laravel-test-app/laravel/packages/lorapok/src/resources/views/widget.blade.php`
- Middleware folder: `src/Middleware` (look for `InjectMonitorWidget` and `TrackExecutionTime`)

Editing & packaging notes
- Keep PSR-4 namespace `Lorapok\\ExecutionMonitor\\` intact; composer.json autoload points at `src/`.
- When making packaging changes, update the `lorapok-package/src` copy and composer metadata in `lorapok-laravel-execution-monitor-v1.0.0/lorapok-package/composer.json`.

If anything here is incomplete or you want examples for a specific task (tests, packaging, or adding a feature), tell me which area to expand and I will update this file.
