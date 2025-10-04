# 42k-app

[![Build](https://github.com/arzamaskov/42k-app/actions/workflows/ci.yml/badge.svg)](https://github.com/arzamaskov/42k-app/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](./LICENSE)
![Version](https://img.shields.io/badge/version-0.1.0-green.svg)

Приложение для учёта и анализа беговых тренировок.  
Monorepo: единый **Laravel** (API + UI через Livewire).

## Структура

```
.
├── ops/ # инфраструктура: docker, nginx, конфиги
│ ├── php/ # Dockerfile + php.ini/opcache.ini/xdebug.ini
│ ├── nginx/ # конфиги nginx
│ └── scripts # служебные скрипты (wait-for, migrate, schedule-loop)
├── app/ # Laravel (модели, миграции, Livewire-компоненты и пр.)
├── database/ # миграции и сиды
├── public/ # веб-корень (index.php, vite build)
└── LICENSE # MIT License
```


## Стек

- **Backend + Frontend**: [Laravel 11](https://laravel.com/) + Livewire 3 + TailwindCSS
- **Database**: PostgreSQL 16 (UUID/ULID идентификаторы, схемы по доменам)
- **Cache/Queue**: Redis 7
- **Mail**: Mailhog (для локальной отладки)
- **Deploy**: Docker Compose (dev), GitHub Actions (сборка по тэгам)

## Инициализация БД

По умолчанию контейнер `db` поднимается пустым. Нужно один раз создать базу `runtracker` и прогнать миграции.

### Пустая база

```bash
# 1) создать БД
docker compose up -d db
docker compose exec db psql -U app -c "CREATE DATABASE runtracker;"

# 2) поднять приложение
docker compose up -d app

# 3) сгенерировать системные таблицы и миграции
docker compose exec app php artisan session:table
docker compose exec app php artisan cache:table
docker compose exec app php artisan queue:table
docker compose exec app php artisan migrate
```

### Из дампа

```bash
docker compose up -d db
docker compose exec db psql -U app -c "CREATE DATABASE runtracker;" || true
# обычный .sql
docker compose exec -T db psql -U app -d runtracker < dump.sql
# затем — на всякий случай — миграции
docker compose up -d app
docker compose exec app php artisan migrate
```

## Запуск локально

```bash
git clone git@github.com:arzamaskov/42k-app.git
cd 42k-app

# поднять сервисы
docker compose up -d --build

# сгенерировать ключ приложения
docker compose exec app php artisan key:generate

# выполнить миграции
docker compose exec app php artisan migrate
```

- Приложение: http://localhost:8888
- Mailhog (почта): http://localhost:8025
- Postgres: localhost:5432 (db: runtracker / user: app / pass: secret)

## Roadmap

- [ ] Auth: регистрация, логин, роли (user/coach/admin)
- [ ] Users: профиль пользователя
- [ ] Runs: учёт пробежек, импорт из Garmin/Strava
- [ ] Training Plans: планы тренировок, календарь (канбан)
- [ ] Dashboard: графики прогресса, статистика нагрузок (через Livewire + Chart.js/Recharts)
- [ ] Notifications: email (Mailhog локально, SMTP в проде), push
- [ ] Integrations: Strava, Garmin Connect, S3 для хранения файлов
- [ ] CI/CD: GitHub Actions, релиз по тэгам, smoke-тесты

## Лицензия

MIT © 2025 Andrey Arzamaskov
