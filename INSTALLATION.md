# Lorapok Installation Guide

## Requirements

- PHP 8.2 or higher
- Laravel 10.x or 11.x
- Composer

## Installation

### Step 1: Install via Composer

```bash
composer require lorapok/laravel-execution-monitor
```

### Step 2: Auto-Configuration

The package automatically:
- Registers the service provider
- Publishes assets
- Enables monitoring in local/dev/staging environments
- Disables in production by default

**No additional configuration needed!**

### Step 3: Verify Installation

Visit your Laravel application in a local/dev environment. You should see a purple floating button in the bottom-right corner with the Lorapok larvae icon (🐛).

## Optional Configuration

### Publish Configuration File

```bash
php artisan vendor:publish --tag=lorapok-config
```

This creates `config/execution-monitor.php` where you can customize:

- Environment detection mode
- Feature toggles (widget, routes, queries, functions)
- Performance thresholds
- Notification channels
- Rate limiting

### Publish Views (Advanced)

```bash
php artisan vendor:publish --tag=lorapok-views
```

This allows you to customize the widget appearance.

## Environment Variables

Add these to your `.env` file for notifications:

```env
# Discord Notifications
MONITOR_DISCORD_WEBHOOK=https://discordapp.com/api/webhooks/your_webhook_id/your_webhook_token
MONITOR_DISCORD_ENABLED=true

# Slack Notifications
MONITOR_SLACK_WEBHOOK=https://hooks.slack.com/services/your_webhook_url
MONITOR_SLACK_ENABLED=true

# Email Notifications
MONITOR_MAIL_TO=admin@example.com
MONITOR_MAIL_ENABLED=true

# Broadcasting (Optional - for real-time alerts)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=ap2
```

## Artisan Commands

```bash
# Check monitoring status
php artisan monitor:status

# Force enable monitoring
php artisan monitor:enable

# Disable monitoring
php artisan monitor:disable

# Run full installation (optional)
php artisan monitor:install
```

## Troubleshooting

### Widget Not Appearing

1. **Check Environment**: Ensure you're in local/dev/staging environment
2. **Clear Cache**: Run `php artisan view:clear` and `php artisan config:clear`
3. **Check Config**: Verify `config/execution-monitor.php` has `'widget' => true`

### Performance Alerts Not Working

1. **Check Thresholds**: Verify thresholds in config are appropriate
2. **Check Webhooks**: Ensure webhook URLs are correct in `.env`
3. **Check Rate Limiting**: Alerts are rate-limited to prevent spam

### Broadcasting Issues

1. **Install Laravel Echo**: `npm install --save laravel-echo pusher-js`
2. **Configure Pusher**: Ensure Pusher credentials are correct
3. **Check Broadcast Driver**: Verify `BROADCAST_DRIVER=pusher` in `.env`

## Package Structure

```
lorapok/
├── src/
│   ├── Commands/          # Artisan commands
│   ├── Events/            # Performance alert events
│   ├── Http/              # Controllers and middleware
│   ├── Notifications/     # Alert notifications
│   ├── Traits/            # Helper traits
│   ├── config/            # Configuration file
│   └── resources/
│       ├── assets/        # JavaScript files
│       └── views/         # Blade templates
├── tests/                 # PHPUnit tests
└── composer.json
```

## Updating

```bash
composer update lorapok/laravel-execution-monitor
```

After updating, clear caches:

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

## Uninstallation

```bash
composer remove lorapok/laravel-execution-monitor
```

Remove published files (if any):

```bash
rm config/execution-monitor.php
rm -rf resources/views/vendor/lorapok
rm -rf public/vendor/lorapok
```

## Support

- **Documentation**: https://maijied.github.io/lorapok/
- **GitHub**: https://github.com/Maijied/lorapok
- **Issues**: https://github.com/Maijied/lorapok/issues
- **Email**: mdshuvo40@gmail.com

## License

MIT License - see LICENSE file for details.

---

Made with ❤️ by [@Maijied](https://github.com/Maijied) - #MaJHiBhai
