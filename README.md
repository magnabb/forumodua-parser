# forumodua parser

## System requirements
* rabbit-mq
* php 7.2+
* postgresql

## Dependencies
* symfony/dotenv

# Install
 0. install docker and docker-compose on your machine
 1. build containers `docker-compose build`
 2. install dependencies `docker run --rm --interactive --tty --volume $PWD:/app --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp composer install`
 3. up containers `docker-compose up`
 4. run parser `todo` `docker run --rm --interactive --tty --volume $PWD:/usr/src/app forumodua-parser_php bin/parse <arguments>`
 
# Doc
* composer install
```bash
```
