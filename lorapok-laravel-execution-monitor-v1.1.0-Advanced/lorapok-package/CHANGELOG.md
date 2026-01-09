# Changelog

All notable changes to the **Lorapok Laravel Execution Monitor** will be documented in this file.

## [1.2.7] - 2026-01-09

### Fixed
- **Error Page Injection**: Improved widget injection logic to accurately target the final `</body>` tag, ensuring the monitor works correctly on Laravel error (500) pages.
- **UI Conflict**: Resolved issue where widget HTML was appearing inside exception code snippets.

## [1.2.5] - 2026-01-09

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
