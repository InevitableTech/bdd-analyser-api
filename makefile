rebuild-db:
	docker-compose run api php artisan migrate:fresh
	docker-compose run api php artisan db:seed

.PHONY: tests
tests:
	docker-compose run api ./vendor/bin/phpunit --config=./phpunit.xml $(f)

command:
	docker-compose run api $(c)