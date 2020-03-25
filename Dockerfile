FROM php:7.4-cli-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install Postgresql extension
RUN set -ex \
	&& apk --no-cache add postgresql-libs postgresql-dev \
	&& docker-php-ext-install pgsql pdo_pgsql \
	&& apk del postgresql-dev

# Install AMQP extension.
RUN apk add --no-cache rabbitmq-c rabbitmq-c-dev $PHPIZE_DEPS \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && apk del $PHPIZE_DEPS

WORKDIR /usr/src/app

CMD ["php", "-a"]