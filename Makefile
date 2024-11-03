build:
	docker compose build --no-cache

start:
	docker compose up --wait

down:
	docker-compose down

logs:
	docker compose logs -f

.PHONY: install

install:
	docker-compose exec php composer require $(p)

install-dev:
	docker-compose exec php composer require --dev $(p)

docs:
	docker run --rm -v "C:\Users\Cesar Arguijo\Documents\GitHub\school-api\api:/project" phpdoc/phpdoc -d /project/src -t /project/docs

docs-tests:
	docker run --rm -v "C:\Users\Cesar Arguijo\Documents\GitHub\school-api\api:/project" phpdoc/phpdoc -d /project/tests -t /project/docs-tests

tests:
	docker-compose exec php bin/phpunit

coverage:
	docker compose exec -e XDEBUG_MODE=coverage php php -d memory_limit=512M ./vendor/bin/phpunit --coverage-html coverage

create-database-tests:
	docker compose exec php bin/console --env=test doctrine:database:create

create-schema-tests:
	docker compose exec php bin/console --env=test doctrine:schema:create

update-schema-tests:
	docker-compose exec php bin/console --env=test doctrine:schema:update --force --complete

reset-schema-tests:
	@docker-compose exec php bin/console --env=test doctrine:schema:drop --force
	@docker-compose exec php bin/console --env=test doctrine:schema:create

view-schema:
	docker-compose exec php bin/console doctrine:schema:list

