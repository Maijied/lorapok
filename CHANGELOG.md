# Changelog

All notable changes to the **Lorapok Laravel Execution Monitor** will be documented in this file.

## [1.3.11] - 2026-01-10

### Added
- **Command Terminal**: Integrated a browser-based CLI for executing `monitor:*` and custom `php artisan` commands directly from the widget.
- **API Playground**: Built-in REST client for testing application endpoints (GET/POST/PUT/DELETE) with JSON body support.
- **Usage Guide**: Interactive modal documented core features, keyboard shortcuts (Ctrl+Shift+C), and advanced pro-tips.
- **Hardcore Achievements**: Added 4 new elite milestones: Zero Gravity, Database Zen, Clean Code Crusader, and Titanium Stability.
- **The Dropbox**: Integrated a direct feedback system in the Developer Profile for dropping "letters" to the author.
- **Service Toggles**: Added granular on/off switches for Routes and Queries monitoring within the terminal.

### Fixed
- **Alpine.js Stability**: Resolved "Cannot read properties of undefined (reading toLowerCase)" error in session activity filtering.
- **Polling Optimization**: Implemented a configurable polling interval (min 1000ms) to reduce server overhead during monitoring.
- **Unified Log UI**: Synchronized Client and Server log tables with clickable rows for consistent detailed inspection.

## [1.3.4] - 2026-01-10

### Added
- **Premium Quests UI**: Redesigned Optimization Quests with glassmorphism, animated 🦋 background larvae, and progress glimmer effects.
- **Server Log Table**: Integrated search and pagination for backend Laravel logs.

### Fixed
- **Settings Configuration**: Restored missing SMTP configuration fields (Host, Port, Auth, Encryption) in the Email settings panel.
- **UI Alignment**: Centered all navigation tabs and unified the control layout for a professional "Standard" aesthetic.
- **Data Stability**: Fixed Alpine.js `length` error when server logs were undefined during initial fetch.

## [1.3.0] - 2026-01-10

### Added
- **UI Enhancements**: Redesigned header action buttons with unique, professional identities (Amber for Quests, Blue for Developer, Indigo for Settings) and improved hover/active states.

### Fixed
- **CI/CD Build**: Resolved critical `BindingResolutionException` in GitHub Actions by correcting workflow path drift and ensuring proper class registration.
- **Navigation Logic**: Moved Quests to the top header for better accessibility and a cleaner main tab interface.

## [1.2.7] - 2026-01-09

### Added
- **Navigation**: Added floating **Home** button to all test lab pages for improved developer experience.
- **System Info**: Added PHP version and Database (driver + version) information to the monitor dashboard footer for better environment visibility.

### Fixed
- **Timeline Enhancement**: Completed "Larvae Trail" (timeline) by adding missing `queries` and `controller` segments.
- **Request Metadata**: Fixed issue where fingerprints showed "N/A" for method and path by ensuring `setRequestData` is called in middleware.
- **Data Isolation**: Implemented `reset()` in `TimelineReporter` to ensure each request starts with a fresh timeline.

## [1.2.4] - 2026-01-09

### Fixed
- **UI Fix**: Resolved `[object Object]` rendering in performance alerts modal by correctly accessing the message property.
- **CI/CD**: Improved synchronization between package source and test application to prevent build drift.

## [1.2.0] - 2026-01-09

### Added
- **Major Refactoring**: Migrated core classes to PSR-4 compliant subdirectories (`Reporters/`, `Services/`).
- **Achievement System**: Fully integrated `AchievementTracker` to track performance milestones (Memory Master, Query Slayer, etc.).
- **Performance Heatmap**: Added `monitor:heatmap` command to visualize route performance distribution.
- **Audit Command**: Added `monitor:audit` command for quick performance and security health checks.
- **Database Support**: Added `monitor_achievements` table for persistent tracking.

### Fixed
- **Class Not Found**: Fixed critical PSR-4 autoloading issues by moving files to correct namespaces.
- **Dashboard Synchronization**: Fixed bug where dashboard requests were unintentionally being recorded in history.
- **Config Consistency**: Harmonized configuration keys (`query_count`, `budgets`) and removed hardcoded thresholds.
- **Security**: Purged hardcoded Slack/Mailtrap tokens and improved privacy masking.
- **UI Feedback**: Fixed query "Copied!" feedback bug in the dashboard.
- **Terminal Compatibility**: Fixed unsupported colors in Artisan commands for better cross-platform compatibility.

## [1.1.1] - 2026-01-09

### Added
- **Verified CI/CD**: Fully functional GitHub Actions for PHPUnit and GitHub Pages.
- **Improved Branding**: Enhanced Developer Profile modal with author photo and social links.
- **Glassmorphism UI**: Redesigned Settings modal to match the premium aesthetic of the Developer Profile.
- **Marketing Assets**: Added professional screenshots and showcase images to README.

### Changed
- Refactored image organization for better GitHub documentation display.
- Optimized widget loading and state persistence.

## [1.0.0] - 2026-01-06

### Added
- Initial release with core monitoring features.
- Floating widget with real-time metrics.
- Query tracking with copy-to-clipboard and history.
- Performance alerts for slow routes and queries.
- Multi-channel notifications (Slack, Discord, Email).
