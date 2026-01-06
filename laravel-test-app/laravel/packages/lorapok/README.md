# 🚀 Lorapok - Laravel Execution Monitor

**Zero-configuration performance monitoring for Laravel applications**

#MaJHiBhai - Your friendly Laravel performance companion 🐛

## ✨ Features

- 🎯 **Zero Configuration** - Works out of the box
- 🤖 **Smart Auto-Detection** - Automatically enables in dev, disables in production
- 🎨 **Beautiful Floating Widget** - Real-time metrics in a clean UI
- ⚡ **Performance Tracking** - Routes, queries, functions, memory
- 🔔 **Alert System** - Slack, Discord, Email notifications
- 📊 **Real-Time Dashboard** - Interactive modal with tabs

## 📦 Installation

```bash
composer require lorapok/laravel-execution-monitor
```

That's it! The package automatically enables in local/dev/staging and disables in production.

### Optional: Full Installation

```bash
php artisan monitor:install
```

## 🚀 Quick Start

```php
use Lorapok\ExecutionMonitor\Facades\Monitor;

// Track execution time
Monitor::start('expensive-operation');
// ... your code
Monitor::end('expensive-operation');

// Or use a closure
$result = monitor('api-call', function() {
    return Http::get('https://api.example.com/data');
});
```

## 🎨 The Widget

A beautiful floating button appears in your application. Click it to see:
- 📊 Overview - Performance metrics at a glance
- 🛣️ Routes - All tracked routes with execution times
- 🗄️ Queries - Database queries with timing
- ⚡ Functions - Custom tracked functions
- 💾 Memory - Memory usage statistics

## 📊 Check Status

```bash
# Check if monitoring is active
php artisan monitor:status

# Force enable
php artisan monitor:enable

# Disable monitoring
php artisan monitor:disable
```

## ⚙️ Configuration

Publish config (optional):

```bash
php artisan vendor:publish --tag=lorapok-config
```

Edit `config/execution-monitor.php`:

```php
return [
    'auto_detect' => true,  // Smart environment detection
    'features' => [
        'widget' => true,
        'routes' => true,
        'queries' => true,
        'functions' => true,
    ],
    'thresholds' => [
        'route' => 1000,   // ms
        'query' => 100,    // ms
    ],
];
```

## 🎭 Environment Modes

| Mode | Local | Staging | Production |
|------|-------|---------|------------|
| `local-only` (default) | ✅ | ❌ | ❌ |
| `non-production` | ✅ | ✅ | ❌ |
| `custom` | Config | Config | Config |

## 📚 Advanced Usage

### In Controllers

```php
use Lorapok\ExecutionMonitor\Traits\TracksExecutionTime;

class UserController extends Controller
{
    use TracksExecutionTime;

    public function index()
    {
        $this->startTimer('user-fetch');
        $users = User::paginate(20);
        $this->endTimer('user-fetch');

        return view('users.index', compact('users'));
    }
}
```

### Helper Functions

```php
// Quick tracking
monitor()->start('task');
// ...
monitor()->end('task');

// With closure
$data = monitor('fetch-data', fn() => $this->getData());

// Check if enabled
if (execution_monitor_enabled()) {
    // monitoring code
}
```

## 🤝 Contributing

Contributions welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md).

## 📄 License

MIT License - see [LICENSE](LICENSE) file.

## 🐛 About Lorapok

Lorapok (inspired by Black Soldier Fly Larvae) helps your Laravel application become more efficient and performant!

**#MaJHiBhai** - Made with ❤️ for the Laravel community
