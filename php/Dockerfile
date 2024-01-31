FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    libzip-dev\
    zip \
    unzip \
    git \
    libonig-dev \
    libpq-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) zip pdo pdo_pgsql pdo_mysql bcmath gd sockets

RUN apt-get install -y autoconf pkg-config libssl-dev 

# Establecer la variable de entorno COMPOSER_ALLOW_SUPERUSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer la variable de entorno COMPOSER_HOME y actualizar el PATH
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www/html