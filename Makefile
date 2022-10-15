.PHONY: install
install: prepare-install build-test migrate build-front

.PHONY: prepare-install
prepare-install:
	cp .env.example .env
	cp .env.test.example .env.test
	docker compose build
	docker compose up -d
	cp apps/symfony/.env.example apps/symfony/.env
	cp apps/symfony/.env.test.example apps/symfony/.env.test
	docker compose exec php composer install

.PHONY: migrate
migrate:
	docker compose exec php bin/console d:s:u --force

.PHONY: migrate
reset-db:
	docker compose -f docker-compose.test.yml exec php bin/console d:s:u --force

.PHONY: build-front
build-front:
	docker compose run -w="/usr/src/app" -u=1000:1000 node yarn
	docker compose run -w="/usr/src/app" -u=1000:1000 node yarn build

##> DEV ENVIRONMENT ##
.PHONY: start
start:
	docker compose up -d

.PHONY: stop
stop:
	docker compose down

.PHONY: format
format:
	docker compose exec php vendor/bin/php-cs-fixer fix

.PHONY: analyse
analyse:
	docker compose exec php ./vendor/bin/phpstan analyse --memory-limit=2G
##< DEV ENVIRONMENT ##

##> TEST ENVIRONMENT ##
.PHONY: start-test
start-test:
	docker compose -f docker-compose.test.yml --env-file=.env.test up -d

.PHONY: stop-test
stop-test:
	docker compose -f docker-compose.test.yml --env-file=.env.test down

.PHONY: build-test
build-test:
	docker compose -f docker-compose.test.yml --env-file=.env.test build

.PHONY: test
test:
	docker compose -f docker-compose.test.yml --env-file=.env.test exec php_test bin/phpunit --colors

reset-db-test:
	docker compose -f docker-compose.test.yml --env-file=.env.test exec php_test bin/console d:s:u --force
	docker compose -f docker-compose.test.yml --env-file=.env.test exec php_test bin/console d:f:l --no-interaction --group=test
##< TEST ENVIRONMENT ##
