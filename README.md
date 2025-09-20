# 42k-app
Minimalist Laravel + Livewire app for tracking runs, shoe mileage, and simple planning — built for 42k.

## Tech
PHP 8.3 • Laravel 11 • Livewire 3 • MySQL 8 • Redis (queues/cache) • Tailwind

## Quick Start
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm i && npm run build
php artisan serve
```

## Scripts
- `queue`: `php artisan queue:work`
- `schedule`: add cron: `* * * * * php artisan schedule:run`

## Env
MAIL_* configured; QUEUE_CONNECTION=redis or database; APP_TIMEZONE=UTC (user timezone per profile).

## Security
Passwords Argon2id, CSRF, rate limits on auth, per‑user policies.

## Roadmap
Import GPX/TCX, Strava/Garmin integrations, advanced load analytics, social feed, badges/goals.
