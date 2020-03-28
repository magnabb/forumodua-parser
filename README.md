# forumodua parser

> Database schema stored in ``migrations/migration.sh``

## System requirements
* rabbitmq
* php 7.2+ (with: pgsql, pdo_pgsql, sockets, amqp)
* postgresql

## Dependencies
* symfony/dotenv
* symfony/dom-crawler
* symfony/http-client
* symfony/browser-kit
* symfony/mime
* symfony/css-selector
* php-amqplib/php-amqplib  
dev:
* roave/security-advisories
* squizlabs/php_codesniffer

# Install
 0. install docker and docker-compose on your machine
 1. build containers `docker-compose build`
 2. install dependencies `docker run --rm --interactive --tty --volume $PWD:/app --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp composer install`
 3. up containers `docker-compose up`. **Consumer already worked!**
 4. run parser `docker-compose bin/parse --parse <url> [--max=<integer>]`.  
 *Example `docker-compose exec php bin/parser --parse https://forumodua.com/showthread.php?t=252286 --max=10`*
 
# Roadmap
1. Add DI-container
2. Add logger
