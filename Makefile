# Disable echoing of commands
MAKEFLAGS += --silent

export UID = $(shell id -u)
export GID = $(shell id -g)

DOCKER_COMPOSE = docker compose -f docker-compose.yml

DOCKER_COMPOSE_RUN = $(DOCKER_COMPOSE) run --no-deps --rm -u "$(UID):$(GID)" -e LOGGING_CONSOLE_LEVEL=info -e LOGGING_LOGGER_LEVEL=info
DOCKER_COMPOSE_EXEC = $(DOCKER_COMPOSE) exec -u "$(UID):$(GID)" -e LOGGING_CONSOLE_LEVEL=info -e LOGGING_LOGGER_LEVEL=info
DOCKER_COMPOSE_EXEC_IT = $(DOCKER_COMPOSE_EXEC) -it

DOCKER_COMPOSE_RUN_BACKEND = $(DOCKER_COMPOSE_RUN) backend
DOCKER_COMPOSE_EXEC_BACKEND = $(DOCKER_COMPOSE_EXEC) backend
DOCKER_COMPOSE_EXEC_IT_BACKEND = $(DOCKER_COMPOSE_EXEC_IT) backend

PHP = $(DOCKER_COMPOSE_RUN_BACKEND) php

SYMFONY_CONSOLE = $(DOCKER_COMPOSE_RUN_BACKEND) symfony console --no-ansi --no-interaction --no-debug

# phony rules
.PHONY: help install-dependencies up reset down show-tokens check-running check-mysql init build rebuild check-node-modules

# variable guard for targets with requirements.
# From https://stackoverflow.com/questions/4728810/how-to-ensure-makefile-variable-is-set-as-a-prerequisite
guard-%:
	@ if [ "${${*}}" = "" ]; then \
		echo "Environment variable $* not set" >&2; \
		echo "Example: make <target> $*=your_value_here " >&2; \
	exit 1; \
	fi

help: ## Print this help.
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-25s\033[0m %s\n", $$1, $$2}'

# check env file and create from .env.dev if not in place
check-env:
	if [ ! -e .env.dev ]; then cp .env.dev .env.dev; echo ".env.dev file not found, created from example! Please check and configure values..."; fi

# check if environment is running
check-running: check-env
	$(DOCKER_COMPOSE_EXEC) backend /bin/sh -c 'echo Backend is running' || ( echo 'Backend is not running!' >&2; exit 3 )
	$(DOCKER_COMPOSE_EXEC) mysql /bin/sh -c 'echo Mysql is running' || ( echo 'Mysql is not running!' >&2; exit 3 )
	$(DOCKER_COMPOSE_EXEC) redis /bin/sh -c 'echo Redis is running' || ( echo 'Redis is not running!' >&2; exit 3 )

check-mysql: SHELL := /bin/bash
check-mysql: check-env
	for i in `seq 1 30`; do sleep 1; $(DOCKER_COMPOSE_EXEC) mysql mysql -uroot -ppassword -e 'select 1' && break || continue; done
	$(DOCKER_COMPOSE_EXEC) mysql mysql -uroot -ppassword -e 'select 1'

# check if environment is running and start if not
ensure-running: check-env
	make check-running || make up

# check if we have vendor directory and install dependencies if not
ensure-php-deps:
	[ -d vendor ] || make install-php-deps

init: check-env down ## Initialize all environment the first time or when versions are updated
	make build
	make clean-php-deps
	make ensure-php-deps
	$(DOCKER_COMPOSE) up -d --force-recreate --remove-orphans --renew-anon-volumes --no-deps --wait mysql redis
	make check-mysql
	$(SYMFONY_CONSOLE) cache:clear
	$(SYMFONY_CONSOLE) cache:pool:clear --all
	make check-mysql
	$(SYMFONY_CONSOLE) doctrine:database:create || true
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate || true
	$(SYMFONY_CONSOLE) assets:install
	@echo "Done."

install-php-deps: check-env down ## Install composer dependencies.
	@echo "Installing composer dependencies..."
	$(DOCKER_COMPOSE_RUN_BACKEND) composer install --dev --ansi --no-cache --no-interaction
	make down
	@echo "Done."

clean-php-deps: check-env down ## Clean composer dependencies.
	@echo "Cleaning composer dependencies..."
	$(DOCKER_COMPOSE_RUN_BACKEND) rm -rf ./vendor/
	make down
	@echo "Done."

clean-deps: clean-php-deps ## Clean all dependencies
	@echo "All dependencies clean."

clean: check-running ## Clean cache
	@echo "Cleaning cache"
	$(SYMFONY_CONSOLE) cache:clear
	$(SYMFONY_CONSOLE) cache:pool:clear --all
	$(SYMFONY_CONSOLE) assets:install
	@echo "Done."

clean-reset: clean-php-deps clean-deps down ## Clean and reset all environment
	rm -rf ./var/cache/* ./var/mysql-data/* ./var/redis-data/* ./.home ./public/bundles
	@echo "Done."

build: down ## Build environment
	@echo "Building docker images..."
	$(DOCKER_COMPOSE) build

rebuild: down ## Rebuild environment, forcing update without cache
	@echo "Building docker images from scratch..."
	$(DOCKER_COMPOSE) build --no-cache --pull
	$(DOCKER_COMPOSE) pull

up: down check-env ensure-php-deps ## Run environment
	$(DOCKER_COMPOSE) up -d --wait --force-recreate --remove-orphans --renew-anon-volumes
	make check-running
	make clean

test: check-env ensure-php-deps ensure-running ## Run tests
	$(DOCKER_COMPOSE_EXEC) -e APP_ENV=test backend php bin/console --env=test doctrine:database:drop --if-exists --force --no-interaction
	$(DOCKER_COMPOSE_EXEC) -e APP_ENV=test backend php bin/console --env=test doctrine:database:create --if-not-exists --no-interaction
	$(DOCKER_COMPOSE_EXEC) -e APP_ENV=test backend php bin/console --env=test doctrine:schema:create >/dev/null 2>/dev/null
	$(DOCKER_COMPOSE_EXEC) -e APP_ENV=test backend php bin/phpunit

down: check-env ## Stop environment (requires environment to be running)
	$(DOCKER_COMPOSE) down --remove-orphans --volumes

shell: ensure-running ## Execute a shell into the php container
	$(DOCKER_COMPOSE_EXEC_IT_BACKEND) /bin/sh

shell-sql: ensure-running ## Execute a shell into the php container
	$(DOCKER_COMPOSE_EXEC_IT) mysql mysql -uroot -ppassword database --skip-binary-as-hex

shell-sql-hex: ensure-running ## Execute a shell into the php container
	$(DOCKER_COMPOSE_EXEC_IT) mysql mysql -uroot -ppassword database

logs: check-running ## Show logs for backend
	$(DOCKER_COMPOSE) logs -f backend
