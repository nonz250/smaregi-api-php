.PHONY: build
build: ## Build command.
	docker compose build

.PHONY: pr
pr: lint test ## Commands to execute before pull request.

.PHONY: lint
lint: ## Fixer command.
	docker compose run --rm app ./vendor/bin/php-cs-fixer fix -vv

.PHONY: test
test: ## Testing command.
	docker compose run --rm app ./vendor/bin/phpunit

# Other commands.
.PHONY: help
help: ## Help command.
	@echo "Usage:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-24s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "and other Make task available. Check Makefile."