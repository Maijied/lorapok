# Agentic Instructions: Lorapok Project

This document serves as a persistent brain for AI agents collaborating on the Lorapok package. Use this as a guide for all future development and maintenance tasks.

## 🎯 Project Identity: #MaJHiBhai
Lorapok is a premium performance monitoring package for Laravel, crafted with love by **Mohammad Maizied Hasan Majumder**.
- **Motto**: "Making Laravel Fast!"
- **Brand Aesthetic**: Glassmorphism, premium dark mode, and "🐛 larvae" iconography.
- **Repository**: `https://github.com/Maijied/lorapok`

## �️ Technical Architecture

### Core Monitoring Engine
The package uses a singleton `Monitor` class (`Lorapok\ExecutionMonitor\Monitor`) to collect and store performance metrics during a single request lifecycle.

- **Request Tracking**: Intercepted via `TrackExecutionTime` middleware.
- **Query Logging**: Captured using `DB::listen` in the `ExecutionMonitorServiceProvider`.
- **UI Injection**: The monitor widget is injected into the HTML response body via `InjectMonitorWidget` middleware (automatically disabled in production).

### Alerts & Notifications
Lorapok supports multi-channel alerts with built-in rate limiting (default: 10 per hour).
- **Channels**: Slack, Discord (direct webhook), Email.
- **Broadcasting**: Supports Laravel Echo/Pusher events for live alerts.

### CLI Tools
- `php artisan monitor:enable/disable`: Toggle monitoring status globally.
- `php artisan monitor:status`: View current configuration and environment status.
- `php artisan monitor:install`: Interactive setup command.

## 📜 Standard Operating Procedures (SOPs)

### 1. Project Memory Navigation
Always check and update `MEMORY.md`. It is the source of truth for the project's development timeline.
- Format: Keep the "Upcoming Tasks" and "Done" sections clear.

### 2. Website & Docs Consistency
The documentation site (`docs/`) must remain synchronized with the package features.
- **Navigation Standard**: All pages (`index`, `docs`, `changelog`, `download`) MUST have the following nav links: `Home`, `Features`, `Installation`, `Documentation`, `Changelog`, `Download`.
- **Anchor Links**: Use `index.html#features` and `index.html#installation` on sub-pages.

### 3. Personal Branding
Never remove the #MaJHiBhai references or the creator's profile information. These are core to the package's identity.

### 4. Release Workflow
- Update `CHANGELOG.md` in the root.
- Sync changes to `docs/changelog.html`.
- Update the version number in `composer.json` (inside `laravel-test-app/laravel/packages/lorapok`).
- Sync all updated files back to the package directory.

## 💡 Pro-Tips for Agents
- When debugging the UI, look at `packages/lorapok/src/resources/views/widget.blade.php`. It uses Alpine.js for interactivity.
- If features aren't showing, check the `allowed_environments` in `config/execution-monitor.php`. 
- **Docker Workflow**: Use the `laravel-test-app` Docker environment for verification. Build with `docker-compose up -d --build`.
