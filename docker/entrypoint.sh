#!/bin/sh

# Exit on error
set -e

echo "🚀 Starting application..."

# Role Check (Optional: if we separate worker/cron containers later)
role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    
    # Run migrations ONLY if configured to do so (safeguard for prod)
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Running migrations..."
        php artisan migrate --force
    fi

    # Caching configuration
    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Start PHP-FPM
    echo "Starting PHP-FPM..."
    exec php-fpm

elif [ "$role" = "queue" ]; then
    echo "Starting Queue Worker..."
    php artisan queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then
    echo "Starting Scheduler..."
    while [ true ]
    do
      php artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

fi
