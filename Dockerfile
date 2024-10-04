FROM php:8.3.12-alpine3.19 AS php

RUN set -eux \
    apk update \
    && apk add --no-cache --update --virtual .build-deps pcre-dev ${PHPIZE_DEPS} \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis.so \
    && apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/app

# Setup php app user
ARG USER_ID=1000
RUN adduser -u ${USER_ID} -D -H app
USER app

COPY --chown=app . /app
WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
