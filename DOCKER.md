# Docker Usage Guide

This guide explains how to run the Lorapok test application using Docker.

## Prerequisites

- Docker Engine (v20.10+)
- Docker Compose (v2.0+)

## Quick Start

1. **Navigate to the test app directory:**
   ```bash
   cd laravel-test-app
   ```

2. **Start the container:**
   ```bash
   docker compose up -d
   ```

3. **Access the application:**
   - Open your browser to `http://localhost:8085`

## Common Commands

### Start the Container
```bash
docker compose up -d
```

### Stop the Container
```bash
docker compose down
```

### Restart the Container
```bash
docker compose restart
```

### View Logs
```bash
docker compose logs -f app
```

### Execute Commands Inside Container
```bash
# Clear Laravel cache
docker compose exec -T app php artisan cache:clear

# Clear config cache
docker compose exec -T app php artisan config:clear

# Clear view cache
docker compose exec -T app php artisan view:clear
```

### Access Container Shell
```bash
docker compose exec app bash
```

## Configuration

The Docker setup includes:
- **PHP 8.4-FPM** with Nginx
- **Port Mapping:** `8085:80` (host:container)
- **Volume Mount:** `./laravel` → `/var/www/html`
- **DNS Servers:** Google DNS (`8.8.8.8`, `8.8.4.4`) for external connectivity

## Troubleshooting

### Connection Issues (cURL error 6)
If you encounter DNS resolution errors, ensure the `dns` configuration is present in `docker-compose.yml`:
```yaml
services:
  app:
    dns:
      - 8.8.8.8
      - 8.8.4.4
```

After modifying `docker-compose.yml`, recreate the container:
```bash
docker compose down
docker compose up -d
```

### Permission Issues
If you encounter permission errors, ensure the Laravel storage and cache directories are writable:
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### View Changes Not Reflecting
Clear the compiled views:
```bash
docker compose exec -T app php artisan view:clear
```

## Network Configuration

The application uses a custom bridge network (`lorapok-network`) for container isolation. External services (Slack, Discord, Email) are accessible via the configured DNS servers.

## Environment Variables

Key environment variables are set in `docker-compose.yml`:
- `APP_ENV=local`
- `APP_DEBUG=true`
- `DB_CONNECTION=sqlite`

For additional configuration, modify the `.env` file in the `laravel` directory.
