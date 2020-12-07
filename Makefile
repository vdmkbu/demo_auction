docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

init: composer-install migrate fixtures

composer-install:
	docker-compose exec php-cli composer install

migrate:
	docker-compose exec php-cli php artisan migrate

fixtures:
	docker-compose exec php-cli php artisan db:seed
