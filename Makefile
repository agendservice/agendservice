up:
	cd docker && docker compose up -d

up_build:
	cd docker && docker compose up -d --build

bash:
	cd docker && docker compose exec app bash

db:
	cd docker && docker compose exec mysql bash

composer_install:
	cd docker && docker compose exec -T app bash -c "composer install"

npm_install:
	cd docker && docker compose exec -T node bash -c "npm install"

env:
	cd docker && docker compose exec -T app bash -c "cp .env.example .env"

down:
	cd docker && docker compose down

key:
	cd docker && docker compose exec app php artisan key:generate

migrate:
	cd docker && docker compose exec app php artisan migrate

style:
	cd docker && docker compose exec -T app bash -c "php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff -vvv"

test:
	cd docker && docker compose exec -T app bash -c "php artisan test"