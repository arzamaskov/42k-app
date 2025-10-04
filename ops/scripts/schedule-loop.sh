#!/usr/bin/env sh
set -e
while true; do
  php artisan schedule:run || true
  sleep 60
done
