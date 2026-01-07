# GitHub Secrets Configuration

To enable full functionality in GitHub Actions, add these secrets to your repository:

## How to Add Secrets

1. Go to: `https://github.com/Maijied/lorapok/settings/secrets/actions`
2. Click "New repository secret"
3. Add each secret below

## Required Secrets

### Pusher Configuration (Optional - for broadcasting tests)

```
PUSHER_APP_ID=2098872
PUSHER_APP_KEY=f397466c990b29486ab0
PUSHER_APP_SECRET=0454aaa16e0339196497
```

### Monitor Discord Webhook (Optional - for notification tests)

```
MONITOR_DISCORD_WEBHOOK=https://discordapp.com/api/webhooks/1458169341764964463/C-Mpiev53RC86-L7G3x_C4eOgImxx9l8f6Fje0N0rKrzqH0hOinEhcQQoT7pdR3_3biU
```

## Notes

- **These secrets are optional** - Tests will run without them
- Notifications are disabled in CI (`MONITOR_NOTIFICATIONS_ENABLED=false`)
- Broadcasting tests will be skipped if Pusher secrets are not set
- The workflow uses `array` driver for cache/session in testing environment
