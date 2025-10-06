# Используем Docker Compose v2 ("docker compose"), но оставим алиас на v1 через переменную
DC ?= docker compose
APP ?= app

.PHONY: up down shell logs migrate deps ensure-env lint lint-fix stan stan-baseline test test-coverage ci

up:
	$(DC) up -d --build

down:
	$(DC) down

shell:
	$(DC) exec $(APP) sh

logs:
	$(DC) logs -f --tail=100

migrate:
	$(DC) exec -T $(APP) ops/scripts/migrate.sh

# ---------- Утилиты ----------
deps: ## Установить composer-зависимости в контейнере
	$(DC) exec -T $(APP) composer install --no-interaction --prefer-dist

ensure-env: ## Скопировать .env при необходимости
	$(DC) exec -T $(APP) sh -lc '[ -f .env ] || cp .env.example .env || touch .env'

# ---------- Качество кода ----------
lint: ## Проверить стиль (Laravel Pint)
	$(DC) exec -T $(APP) vendor/bin/pint --test

lint-fix: ## Исправить стиль (Laravel Pint)
	$(DC) exec -T $(APP) vendor/bin/pint

stan: ## Статический анализ (PHPStan/Larastan)
	$(DC) exec -T $(APP) php -d memory_limit=-1 vendor/bin/phpstan analyse --no-progress

stan-baseline: ## Сгенерировать baseline для PHPStan
	$(DC) exec -T $(APP) php -d memory_limit=-1 vendor/bin/phpstan analyse --generate-baseline=phpstan-baseline.neon

# ---------- Тесты ----------
test:
	$(DC) exec $(APP) php artisan test

phpunit: ensure-env ## Запустить тесты
	$(DC) exec -T $(APP) php artisan key:generate --no-interaction || true
	$(DC) exec -T $(APP) php artisan migrate --graceful --no-interaction || true
	$(DC) exec -T $(APP) vendor/bin/phpunit --colors=always

# Потребуется xdebug в контейнере для покрытия; если нет — можно убрать этот таргет
test-coverage: ensure-env
	$(DC) exec -T $(APP) php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text --colors=always

# ---------- Комбо для локальной проверки перед пушем ----------
ci: deps lint stan phpunit ## Локальный аналог CI-гонки
