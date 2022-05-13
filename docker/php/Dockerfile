FROM php:fpm

WORKDIR /usr/src/app

COPY --chown=1000:1000 ./ /usr/src/app
RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN pecl install apcu xdebug

RUN docker-php-ext-install pdo_mysql mysqli zip
RUN docker-php-ext-enable apcu xdebug

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer
