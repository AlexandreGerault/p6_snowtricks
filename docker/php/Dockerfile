FROM php:fpm

WORKDIR /usr/src/app

COPY --chown=1000:1000 ./ /usr/src/app
RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libmagickwand-dev \
    libpng-dev \
    libjpeg-dev \
    libssl-dev \
    libfreetype6-dev

RUN pecl install apcu imagick xdebug
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install bcmath exif gd mysqli pdo_mysql zip
RUN docker-php-ext-enable apcu imagick xdebug

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer
