# 🐛 Lorapok - Advanced Laravel Execution Monitor (v1.1.0-Advanced)

**Zero-configuration, real-time performance intelligence for Laravel applications.**

This document summarizes the full feature set of the Lorapok package, designed to help developers identify bottlenecks and optimize their Laravel applications with ease.

---

## 📊 1. Core Monitoring & Profiling
*   **Real-time Performance Metrics:** Tracks execution time, memory usage (current & peak), and database queries for every request.
*   **Smart Auto-Detection:** Automatically enables in `local`, `dev`, and `staging` environments and disables in `production`.
*   **Detailed Timeline (Larvae Trail):** Visualizes the entire request lifecycle (booting, middleware, route, controller, view rendering).
*   **Route Tracking:** Captures HTTP method, path, status codes, and controller actions.
*   **Exception Tracking:** Automatically logs application exceptions with full context for easier debugging.

## 🎨 2. Interactive Widget (UI)
*   **Floating Dashboard:** A sleek, non-intrusive floating button that expands into a full-featured modal.
*   **Glassmorphism Design:** Modern, polished UI with smooth animations and responsive layout.
*   **Tabbed Information:**
    *   **Overview:** High-level performance summary.
    *   **Timeline:** Gantt-chart style visualization of execution flow.
    *   **Routes:** Detailed route-specific metrics.
    *   **Queries:** Every SQL query executed, including bindings and precise timing.
    *   **Middleware:** Insight into the middleware stack for the current request.
*   **Developer Profile:** Integrated profile (#MaJHiBhai) with quick links and feedback channels.

## 🧠 3. Advanced Analysis & Insights
*   **Query Intelligence:**
    *   **Pattern Detection:** Identifies N+1 problems and duplicate queries automatically.
    *   **Optimization Tips:** Actionable suggestions to improve Eloquent performance.
    *   **Slow Query Highlighting:** Visual cues for queries that exceed threshold limits.
*   **Cache ROI Analyzer:** Monitors cache hits/misses to calculate the effectiveness of your caching strategy.
*   **View Profiler:** Pinpoints slow-rendering Blade templates.
*   **Performance Budgets:** Set specific limits for execution time and memory; receive alerts when they are breached.

## 🛠️ 4. Developer Power Tools
*   **Command Terminal:** Run `php artisan` commands directly from your browser via the widget.
*   **API Playground:** A built-in REST client to test GET/POST endpoints instantly.
*   **Unified Log Viewer:** Combined view of client and server-side logs with date filtering and search.
*   **Clipboard Integration:** One-click SQL copying with `Ctrl+Shift+C` support and a history of the last 20 copied items.
*   **Service Toggles:** Enable or disable specific monitoring features (e.g., toggle query tracking) on the fly without refreshing.

## 🔔 5. Notifications & Real-Time Alerts
*   **Multi-Channel Support:** Native integration with **Discord**, **Slack**, and **Email**.
*   **Automated Alerts:** Instant notifications for slow routes, slow queries, and budget breaches.
*   **Broadcasting:** Real-time updates delivered via Laravel Echo/Pusher.

## 💻 6. CLI & Artisan Commands
*   `monitor:install`: Quick setup of assets and configuration.
*   `monitor:status`: Instant check of the monitoring state.
*   `monitor:audit`: Runs a comprehensive performance and security health check.
*   `monitor:heatmap`: Identifies the most consistently slow routes in your application.
*   `monitor:enable` / `monitor:disable`: Manual control over the monitoring state.
*   `monitor:memory`: Deep dive into memory allocation patterns.
*   `monitor:replay`: Re-run specific requests to analyze behavior.

## 🔒 7. Security & Privacy
*   **Security Scanner:** Checks for common vulnerabilities and misconfigurations.
*   **Privacy Masker:** Automatically redacts sensitive information (passwords, tokens) from logs and traces.
*   **Environment Safety:** Hard-coded guards to ensure monitoring never leaks into production unless explicitly configured.

## 🎮 8. Testing & Gamification
*   **Achievement System:** Earn persistent badges (e.g., "Memory Master", "Query Slayer") as you optimize your app.
*   **Built-in Test Lab:** Pre-configured routes to simulate performance issues and verify monitor alerts:
    *   `/lorapok/test/slow-route`
    *   `/lorapok/test/slow-query`
    *   `/lorapok/test/many-queries`
    *   `/lorapok/test/high-memory`
    *   `/lorapok/test/exception`

---

**Developed with ❤️ for the Laravel Community by Mohammad Maizied Hasan Majumder (#MaJHiBhai)**
