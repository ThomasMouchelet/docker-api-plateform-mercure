ifeq ($(ENV), dev)
	compose_file := docker-compose-dev.yml
	file_env_npm_name := .env.dev
else
	compose_file := docker-compose-prod.yml
	file_env_npm_name := .env.prod
endif

de := docker exec docker-container-php
sy := $(de) php bin/console
dc := docker-compose -f $(compose_file)

.PHONY: down
down: ## Down docker-compose.yml file
	$(dc) down --remove-orphans

.PHONY: up
up: ## Up docker-compose.yml file
	$(dc) up -d --build

.PHONY: install
install: up ## Installer les dépendances composer
	$(de) composer install

.PHONY: node_modules
node_modules: ## Installer les dépendances npm
	cd app && npm install && cd ..

.PHONY: migrations
migrations: install ## Génère les tables dans la base de données
	$(de) php bin/console doctrine:migrations:migrate -q

.PHONY: fixtures
fixtures: ## Génère de fausses données dans la base de données
	$(de) php bin/console doctrine:fixtures:load -q

.PHONY: env_prod
env_prod: 
	ENV=prod

.PHONY: env_dev
env_dev: 
	ENV=dev

.PHONY: file_env_npm
file_env_npm: ## Migrer les variables d'environement dans le .env
	cd app && envsubst < $(file_env_npm_name) > .env && cd ..

.PHONY: dev
dev: env_dev file_env_npm node_modules up install migrations fixtures ## -- Initialiser le projet en développement

.PHONY: prod
prod: env_prod file_env_npm up install migrations ## -- Initialiser le projet en production

.PHONY: reset
reset: ## Delete all volumes and all images
	docker volume rm $$(docker volume ls -q) && docker rmi $$(docker images -q) 

.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: jwt_keys
jwt_keys: ## Génére les clés jwt
	$(de) php bin/console lexik:jwt:generate-keypair --overwrite

.PHONY: deploy
deploy: ## déployer le projet
	ssh user@yourip 'cd docker-project-name && git pull origin master && make prod ENV=prod && make jwt_keys'
	
.PHONY: clear
clear: ## clear cache
	$(sy) cache:clear