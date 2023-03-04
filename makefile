rebuild-db:
	docker-compose run api php artisan migrate:fresh
	docker-compose run api php artisan db:seed

update:
	docker-compose run api composer update

install-dirs:
	docker-compose run api mkdir -p storage/logs bootstrap/cache

run:
	docker-compose up -d
	docker-compose run api php artisan config:clear

.PHONY: tests
tests:
	docker-compose run api ./vendor/bin/phpunit --config=./phpunit.xml $(f)

e2e:
	docker-compose run api ./vendor/bin/phpunit --config=./phpunit.xml ./tests/Integration/EndToEndTest.php

command:
	docker-compose run api $(c)

.PHONY: artisan
artisan:
	docker-compose run api php artisan $(a)

chown:
	sudo chown -R forceedge01:forceedge01 app config tests