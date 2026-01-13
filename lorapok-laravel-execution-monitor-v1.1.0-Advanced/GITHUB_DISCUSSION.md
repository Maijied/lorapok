# 🐛 Lorapok v1.1.0-Advanced — Real-Time Laravel Performance Intelligence Arrives

Hey Laravel Community! 👋

I am thrilled to announce the release of **Lorapok v1.1.0-Advanced**, a zero-configuration performance monitoring suite designed to bring deep, real-time insights to your Laravel development workflow.

Inspired by the efficient Black Soldier Fly Larvae, **Lorapok** (pronounced *Lo-ra-pok*) works quietly in the background to identify bottlenecks, optimize queries, and eliminate performance bloat before it hits production.

---

## 🚀 Why Lorapok?

We built Lorapok because performance monitoring shouldn't be a chore. It should be instant, visual, and actionable. 

*   **Zero Configuration:** Install via composer and it’s alive. No manual service providers or complex setup.
*   **Smart Environment Detection:** Automatically active in `local`/`dev`/`staging` and stays out of the way in `production`.
*   **Beautiful Glassmorphism UI:** A sleek, non-intrusive floating widget that expands into a modern dashboard.

## 🔥 Key Feature Highlights

### 📊 The Larvae Trail (Execution Timeline)
A visual Gantt-chart style timeline of your entire request. See exactly how much time is spent in:
*   **Booting & Middleware**
*   **Route Handling**
*   **Controller Logic**
*   **View Rendering**

### 🗄️ Query Intelligence & History
*   **N+1 Detection:** Automatically flags redundant database calls.
*   **One-Click Copy:** Fast SQL copying with `Ctrl+Shift+C` support.
*   **Clipboard History:** Keeps track of your last 20 copied queries for quick reference.

### 🛠️ Integrated Developer Tools
*   **Browser Terminal:** Run `php artisan` commands directly from the UI widget.
*   **API Playground:** A built-in REST client to test your endpoints on the fly.
*   **Unified Log Viewer:** Combined Client/Server logs with smart filtering.

### 🔔 Real-Time Alerts
Get notified instantly via **Discord**, **Slack**, or **Email** when:
*   Queries exceed your defined thresholds.
*   Routes are running slow.
*   Memory usage spikes unexpectedly.

---

## 📦 Getting Started

Install it in seconds:

```bash
composer require lorapok/laravel-execution-monitor
```

Once installed, simply look for the purple 🐛 icon in the bottom-right of your application!

## 🔗 Resources

*   **GitHub Repository:** [github.com/Maijied/lorapok](https://github.com/Maijied/lorapok)
*   **Full Documentation:** [maijied.github.io/lorapok](https://maijied.github.io/lorapok/docs.html)

---

I'd love to hear your thoughts! What's the one thing that slows down your Laravel dev loop? Let's discuss below! 👇

Happy Coding! 🚀