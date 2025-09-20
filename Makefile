# Используем Docker Compose v2 ("docker compose"), но оставим алиас на v1 через переменную
DC ?= docker compose

up:
	$(DC) up -d --build

down:
	$(DC) down

shell:
	$(DC) exec app sh

# Безопасный бутстрап: развернуть Laravel во временную папку и скопировать в ./app
bootstrap-laravel-safe:
	$(DC) run --rm app sh -lc '\
	set -e; \
	if [ ! -f /var/www/html/composer.json ]; then \
	  echo ">> Fresh bootstrap: creating Laravel in /tmp/laravel"; \
	  rm -rf /tmp/laravel; \
	  composer create-project laravel/laravel:^11.0 /tmp/laravel; \
	  cp -R /tmp/laravel/. /var/www/html/; \
	  rm -rf /tmp/laravel; \
	else \
	  echo ">> composer.json found; skipping create-project"; \
	fi; \
	composer config platform.php 8.3; \
	composer require livewire/livewire:^3.5 predis/predis:^2.2; \
	composer require --dev laravel/breeze:^2.1; \
	php artisan breeze:install blade --no-interaction; \
	php artisan key:generate; \
	npm ci || npm i; \
	npm run build'

# Старый вариант (ожидает пустую ./app)
bootstrap-laravel:
	$(DC) run --rm app composer create-project laravel/laravel:^11.0 .
	$(DC) run --rm app composer config platform.php 8.3
	$(DC) run --rm app composer require livewire/livewire:^3.5 predis/predis:^2.2
	$(DC) run --rm app composer require --dev laravel/breeze:^2.1
	$(DC) run --rm app php artisan breeze:install blade --no-interaction
	$(DC) run --rm app php artisan key:generate
	$(DC) run --rm app npm ci || npm i
	$(DC) run --rm app npm run build

migrate:
	$(DC) exec app php artisan migrate

queue:
	$(DC) exec app php artisan queue:work --queue=default --sleep=3 --tries=3
