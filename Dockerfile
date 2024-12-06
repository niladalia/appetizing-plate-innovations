# We need to define all ARGs used in root scope (used in FROM statements) because if not, docker will fail to resolve them
ARG COMPOSER_BASE_IMAGE=composer
ARG COMPOSER_BASE_IMAGE_TAG=2.6.6
ARG PHP_BASE_IMAGE=dunglas/frankenphp
ARG PHP_BASE_IMAGE_TAG=1.1.0-php8.3.2-alpine

# Base image requirements. This image will be cached globally at repository level for all branches as it should not change very often
FROM ${PHP_BASE_IMAGE}:${PHP_BASE_IMAGE_TAG} AS base

RUN true \
 && apk add --update --no-cache \
  bash \
  curl \
  nodejs \
  npm \
  acl \
  file \
  gettext \
  git \
 && install-php-extensions \
  apcu \
  pdo_mysql \
  gd \
  intl \
  zip \
  opcache \
  redis-6.0.2 \
  igbinary-3.2.16 \
;


ARG SYMFONY_CLI_VERSION=5.8.6
ARG TARGETPLATFORM

RUN if [ "$TARGETPLATFORM" = "linux/amd64" ]; then ARCHITECTURE=x86_64; elif [ "$TARGETPLATFORM" = "linux/arm/v7" ]; then ARCHITECTURE=armhf; elif [ "$TARGETPLATFORM" = "linux/arm64" ]; then ARCHITECTURE=aarch64; else ARCHITECTURE=x86_64; fi ; true \
 && cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
 && sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' $PHP_INI_DIR/php.ini \
 && rm -rf /etc/caddy/Caddyfile \
 && ln -s /app/Caddyfile /etc/caddy/Caddyfile \
 && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
 && curl -1sLf "https://github.com/symfony-cli/symfony-cli/releases/download/v${SYMFONY_CLI_VERSION}/symfony-cli_${SYMFONY_CLI_VERSION}_${ARCHITECTURE}.apk" >/tmp/symfony.apk \
 && apk add --allow-untrusted /tmp/symfony.apk \
 && rm -rf /tmp/symfony.apk \
;

ENV PHP_INI_SCAN_DIR=:/app/docker/php
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV XDEBUG_MODE=off







# The following block is needed because in docker you can't expand arg variables in "COPY --from="
FROM ${COMPOSER_BASE_IMAGE}:${COMPOSER_BASE_IMAGE_TAG} AS composer-base







# Development image. It has xdebug + php.ini development configuration,
# and does not have any code as we expect the root project dir to be mounted at /app
FROM base AS dev

RUN true \
 && install-php-extensions \
  xdebug \
;

RUN true \
 && cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini \
 && sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' $PHP_INI_DIR/php.ini \
;

# Use image alias because docker doesn't expand arg variables here
COPY --from=composer-base /usr/bin/composer /usr/bin/composer

ENV APP_ENV=dev
ENV APP_DEBUG=true
ENV COMPOSER_VENDOR_DIR=/app/vendor
ENV HOME /app/.home
ENV APP_ENV=dev

WORKDIR /app

ARG UID=33

# Caddy requires an additional capability to bind to port 80 and 443
# Caddy requires write access to /data/caddy and /config/caddy
RUN true \
 && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
 && chown -R ${UID}:${UID} /data/caddy \
 && chown -R ${UID}:${UID} /config/caddy \
;

WORKDIR /app




# Dependencies image. It has the base image + composer dependencies installed as root user in /composer/vendor to enable better caching.
# We use root user so the /composer/vendor files won't be writable by the runtime user, and to enable assigning a custom user/UID in
# the child image so we can extend from this cached image and set the user later without having to rebuild dependencies.
# We will cache this image globally for tags, then locally for each branch
FROM base AS dependencies

# Use image alias because docker doesn't expand arg variables here
COPY --from=composer-base /usr/bin/composer /usr/bin/composer

ENV COMPOSER_VENDOR_DIR=/composer/vendor
ENV APP_ENV=prod

WORKDIR /app
COPY composer.json composer.lock /app/
RUN true \
 && mkdir -p /composer/vendor \
 && composer install --no-dev --ansi --prefer-dist --no-interaction --no-progress --no-scripts --no-cache --optimize-autoloader --classmap-authoritative \
;













# Final image. We extend from dependencies image so here we will only have the application code and the user/UID configuration, enabling a better caching for builds
FROM dependencies AS runtime

ARG USER=www-data
ARG UID=33

# Caddy requires an additional capability to bind to port 80 and 443
# Caddy requires write access to /data/caddy and /config/caddy
RUN true \
 && ( deluser ${USER} 2>/dev/null || true ) \
 && ( deluser $(getent passwd ${UID} | cut -d':' -f1) 2>/dev/null || true ) \
 && adduser -D -h /apphome -u ${UID} ${USER} \
 && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp \
 && chown -R ${USER}:${USER} /data/caddy \
 && chown -R ${USER}:${USER} /config/caddy \
 && chown -R ${USER}:${USER} /composer/vendor/composer \
;

COPY --chown=${USER}:${USER} . /app/

RUN true \
 && chown ${USER}:${USER} /app \
;

USER ${USER}

ENV HOME=/apphome

WORKDIR /app

RUN true \
 && mkdir -p var/cache var/log \
 && composer dump-autoload --classmap-authoritative --no-dev \
 && composer dump-env prod \
 && composer run-script --no-dev post-install-cmd \
 && chmod +x bin/console \
;

