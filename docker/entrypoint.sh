#!/usr/bin/env bash
set -e

# Google Cloud Run injects $PORT (defaults to 8080)
PORT="${PORT:-8080}"
echo "AICHost Cloud Run starting on port ${PORT}..."

# Replace port placeholder in Apache config
sed -i "s/\${PORT}/${PORT}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf 2>/dev/null || true

# Cache Laravel configurations if in production
if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Hand over execution to Apache foreground process
exec apache2-foreground
