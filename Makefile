.DEFAULT_GOAL := help
.PHONY: *

help: ## Print Help
	@printf "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

cs: ## Let PHP CSFixer do the job
	php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --allow-risky=yes

phpunit: ## PHPUnit is your friend - Run all Tests
	XDEBUG_MODE=coverage php vendor/bin/phpunit -c phpunit.xml.dist --coverage-text --coverage-html .build

stan: ## PHPSTAN Check <3
	symfony php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M --xdebug

psalm: ## Psalm Check
	symfony php vendor/bin/psalm
