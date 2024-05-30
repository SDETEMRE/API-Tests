SHELL:=/bin/bash
-include Makefile.env.dist

.SILENT:
.DEFAULT_GOAL := help

.PHONY: setup
setup: restart composer-install tool-install-all database-migrate        ## Setup the Docker environment

.PHONY: start
start:                                                                   ## Start the Docker environment
	export DOCKER_BUILDKIT=1
	docker compose -f ${DOCKER_COMPOSE_FILE_DIR} up --build -d

.PHONY: stop
stop:                                                                    ## Stop the Docker environment
	docker compose -f ${DOCKER_COMPOSE_FILE_DIR} down --remove-orphans

.PHONY: restart
restart: stop start                                                      ## Restart the Docker environment

.PHONY: status
status:                                                                  ## Show weather the Docker services are running or not
	docker compose -f ${DOCKER_COMPOSE_FILE_DIR} ps --all

.PHONY: console
console:                                                                        ## Open a bash console in the php Docker environment
	docker compose -f ${DOCKER_COMPOSE_FILE_DIR} exec $(ARGS) php sh

.PHONY: database-generate-migration
database-generate-migration:                                                    ## Generate a migration file
	${BIN_PHP_NO_DEBUG} ./bin/console doctrine:migrations:generate

.PHONY: database-migrate
database-migrate:                                                               ## Execute migrations on MySQL database
	${BIN_PHP_NO_DEBUG} ./bin/console doctrine:migrations:migrate --no-interaction

.PHONY: import-logs
import-logs:                                                               ## ## Executes Import logs
	${BIN_PHP_NO_DEBUG} ./bin/console app:service-logs:store

.PHONY: analysis
analysis: start test cs-check phpstan deptrac lint security-check        ## Run the static analysis, linting/validator tools and a security report for composer to show known vulnerabilities

.PHONY: clean
clean: clean-cache clean-log                                                    ## Clean any generated file
	cd app
	find ./app/bin-vendor -type d -name vendor -exec chmod 755 {} \;
	find ./app/bin-vendor -type d -name vendor -exec rm -rf {} \;
	rm -rf .deptrac.cache .php_cs.cache .php-cs-fixer.cache .phpunit.result.cache \
      config/secrets/prod/prod.decrypt.private.php doc/architecture/build \
      phpunit.xml reports vendor \
      && make docker-stop

.PHONY: clean-cache
clean-cache: start                                                       ## Clean cache directories
	cd app
	${BIN_PHP_NO_DEBUG} ./bin/console cache:clear --no-debug
	${BIN_PHP_NO_DEBUG} ./bin/console cache:clear --env=prod --no-debug
	${BIN_PHP_NO_DEBUG} ./bin/console cache:clear --env=stage --no-debug
	rm -f .php_cs.cache .phpunit.result.cache

.PHONY: clean-log
clean-log:                                                                      ## Clean log directory
	cd app
	rm -rf var/log/*

.PHONY: clean-setup
clean-setup: clean setup                                                        ## Clean everything before executing a setup

.PHONY: composer-bump-deps
composer-bump-deps:                                                             ## Run composer bump
	cd app
	${BIN_PHP_NO_DEBUG} ./composer.phar bump --ansi

.PHONY: composer-check
composer-check:                                                                 ## Check information about the current system to identify common errors
	cd app
	${BIN_PHP_NO_DEBUG} ./composer.phar diagnose --ansi

.PHONY: composer-install
composer-install:                                                               ## Run composer install
	cd app
	${BIN_PHP_NO_DEBUG} ./composer.phar install --ansi --no-interaction --no-scripts

.PHONY: composer-update
composer-update:                                                                ## Run composer update
	cd app
	${BIN_PHP_NO_DEBUG} ./composer.phar update --ansi --no-interaction --no-scripts

.PHONY: cs-fix
cs-fix:                                                                         ## Run code style checker fix
	${BIN_PHP_NO_DEBUG} ./bin-vendor/php-cs-fixer/vendor/bin/php-cs-fixer fix --config=.qa/.php_cs.dist.php --no-interaction --ansi

.PHONY: deptrac
deptrac:                                                                        ## Run deptrac setup
	${BIN_PHP_NO_DEBUG} ./bin-vendor/deptrac/vendor/bin/deptrac analyse --config-file=.qa/hexagonal.yaml
	${BIN_PHP_NO_DEBUG} ./bin-vendor/deptrac/vendor/bin/deptrac analyse --config-file=.qa/business-logic.yaml
	${BIN_PHP_NO_DEBUG} ./bin-vendor/deptrac/vendor/bin/deptrac analyse --config-file=.qa/3rd-party.yaml --report-uncovered --fail-on-uncovered

.PHONY: dev-check
dev-check: cs-fix phpstan deptrac                                             ## Run analysis tools to check development QA code tools

.PHONY: lint
lint: lint-composer lint-container lint-docker lint-yaml                        ## Run all the available linter we have setup

.PHONY: lint-composer
lint-composer:                                                                  ## Run composer validate
	${BIN_PHP_NO_DEBUG} ./composer.phar validate --ansi --check-lock --strict

.PHONY: lint-container
lint-container:                                                                 ## Run Symfony console lint:container command
	${BIN_PHP_NO_DEBUG} ./bin/console lint:container

.PHONY: lint-docker
lint-docker:                                                                    ## Run hadolint to check the Dockerfiles follow best practices
	docker run --init --rm -v "$$(pwd):/project" -w /project hadolint/hadolint:v2.12.0-alpine find . -type f -name 'Dockerfile_*' -exec hadolint {} \;

.PHONY: lint-yaml
lint-yaml:                                                                      ## Run Symfony console lint:yaml command
	${BIN_PHP_NO_DEBUG} ./bin/console lint:yaml config --parse-tags

.PHONY: phpstan
phpstan:                                                                        ## Run phpstan setup
	${BIN_PHP_NO_DEBUG} ./bin-vendor/phpstan/vendor/bin/phpstan analyse --configuration=.qa/phpstan.neon --ansi $(ARGS)

.PHONY: security-check
security-check:                                                                 ## Run security report for composer dependencies to show known vulnerabilities
	${BIN_PHP_NO_DEBUG} ./composer.phar audit --ansi --no-plugins --no-scripts $(ARGS)

.PHONY: symfony-check
symfony-check:                                                                  ## Check information about the current Symfony installation
	${BIN_PHP_NO_DEBUG} ./bin/console --version --ansi
	${BIN_PHP_NO_DEBUG} ./bin/console about --ansi
	${BIN_PHP_NO_DEBUG} ./composer.phar recipes --ansi

.PHONY: symfony-console
symfony-console:                                                                ## Run the Symfony console command-line
	${BIN_PHP_NO_DEBUG} ./bin/console --no-debug $(ARGS)

.PHONY: test
test:                                                                           ## Run all the test suites except the end-to-end (behat)
	${BIN_PHP} ./vendor/bin/phpunit $(ARGS)

.PHONY: test-coverage
test-coverage:                                                                  ## Generate a test coverage report
	docker compose -f ${DOCKER_COMPOSE_FILE_DIR} exec -T php php -d 'xdebug.mode=coverage' ./vendor/bin/phpunit --testsuite=Coverage --coverage-html reports $(ARGS)

.PHONY: test-integration
test-integration:                                                               ## Run the integration tests
	${BIN_PHP} ./vendor/bin/phpunit --testsuite=Integration $(ARGS)

.PHONY: test-unit
test-unit:                                                                      ## Run the unit tests
	${BIN_PHP} ./vendor/bin/phpunit --testsuite=Unit $(ARGS) --testdox

.PHONY: test-behat
test-behat:                                                                     ## Run the end-to-end tests
	${BIN_PHP} ./vendor/bin/behat --colors --strict --format progress $(ARGS)

.PHONY: tool-install-all
tool-install-all: start tool-install-deptrac tool-install-infection tool-install-php-cs-fixer tool-install-phpstan  ## Install all development tools found in app/bin-vendor

.PHONY: tool-install-deptrac
tool-install-deptrac: start                                              ## Install Deptrac tool
	${BIN_PHP_NO_DEBUG} ./composer.phar install --ansi --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/deptrac

.PHONY: tool-install-infection
tool-install-infection: start                                            ## Install infection PHP tool
	${BIN_PHP_NO_DEBUG} ./composer.phar install --ansi --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/infection

.PHONY: tool-install-php-cs-fixer
tool-install-php-cs-fixer: start                                         ## Install PHP CS Fixer tool
	${BIN_PHP_NO_DEBUG} ./composer.phar install --ansi --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/php-cs-fixer

.PHONY: tool-install-phpstan
tool-install-phpstan: start                                              ## Install PHPStan tool
	${BIN_PHP_NO_DEBUG} ./composer.phar install --ansi --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/phpstan

.PHONY: tool-update-all
tool-update-all: start tool-update-composer tool-update-deptrac tool-update-infection tool-update-php-cs-fixer tool-update-phpstan  ## Update composer.phar + all the development tools found in app/bin-vendor

.PHONY: tool-update-composer
tool-update-composer: start                                              ## Update composer.phar PHP tool
	${BIN_PHP_NO_DEBUG} ./composer.phar self-update

.PHONY: tool-update-deptrac
tool-update-deptrac: start                                               ## Update Deptrac tool
	${BIN_PHP_NO_DEBUG} ./composer.phar update --ansi --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/deptrac

.PHONY: tool-update-infection
tool-update-infection: start                                             ## Update infection PHP tool
	${BIN_PHP_NO_DEBUG} ./composer.phar update --ansi --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/infection

.PHONY: tool-update-php-cs-fixer
tool-update-php-cs-fixer: start                                          ## Update PHP CS Fixer tool
	${BIN_PHP_NO_DEBUG} ./composer.phar update --ansi --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/php-cs-fixer

.PHONY: tool-update-phpstan
tool-update-phpstan: start                                               ## Update PHPStan tool
	${BIN_PHP_NO_DEBUG} ./composer.phar update --ansi --no-progress --prefer-dist --optimize-autoloader --working-dir=./bin-vendor/phpstan

.PHONY: help
help:                                                                           ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
