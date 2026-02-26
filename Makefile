up:
	docker compose up -d

up_build:
	docker compose up -d --build

bash:
	docker compose exec php bash

db:
	docker compose exec mysql bash

composer_install:
	docker compose exec -T php bash -c "composer install"

down:
	docker compose down

migrate:
	docker compose exec php artisan migrate
