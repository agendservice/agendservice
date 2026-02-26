up:
	cd docker && docker compose up -d

up_build:
	cd docker && docker compose up -d --build

bash:
	cd docker && docker compose exec php bash

db:
	cd docker && docker compose exec mysql bash

composer_install:
	cd docker && docker compose exec -T php bash -c "composer install"

down:
	cd docker && docker compose down

migrate:
	cd docker && docker compose exec app php artisan migrate
