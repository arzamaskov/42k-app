#!/usr/bin/env sh
set -e

# только миграции; если нужно — вручную:
# php artisan session:table
# php artisan cache:table
# php artisan queue:table
php artisan migrate --force

# опционально (раскомментировать на проде):
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache
