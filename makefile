rebuild-db:
	docker-compose run api php artisan migrate:fresh
	docker-compose run api php artisan db:seed

update:
	docker-compose run api composer update

run:
	docker-compose up -d
	docker-compose run api php artisan config:clear

.PHONY: tests
tests:
	docker-compose run api ./vendor/bin/phpunit --config=./phpunit.xml $(f)

command:
	docker-compose run api $(c)

.PHONY: artisan
artisan:
	docker-compose run api php artisan $(a)