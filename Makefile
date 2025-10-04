# Используем Docker Compose v2 ("docker compose"), но оставим алиас на v1 через переменную
DC ?= docker compose

up:
	$(DC) up -d --build

down:
	$(DC) down

shell:
	$(DC) exec app sh

logs:
	$(DC) logs -f --tail=100

migrate:
	$(DC) exec app ops/scripts/migrate.sh
