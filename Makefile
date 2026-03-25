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
	cd docker && docker compose exec -T app bash -c "npm install"

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

coverage:
	cd docker && docker compose exec -T app bash -c "./vendor/bin/phpunit --coverage-text"

perm:
	cd docker && docker compose exec -u root app chmod -R 777 storage bootstrap/cache

seed:
	cd docker && docker compose exec -T app bash -c "php artisan db:seed"