# Base image
FROM php:8.2-fpm as base

# Args
ARG ENV

# Set main params
ENV APP_HOME /var/www/html

# Install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-configure gd --enable-gd --with-jpeg \
    && docker-php-ext-install \
      pdo_pgsql \
      sockets \
      opcache \
      pcntl\
      gd \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

# Install php-redis
RUN pecl install redis && \
    docker-php-ext-enable redis

# Set working directory
WORKDIR $APP_HOME

# BUILD PROD IMAGE
FROM base as prod

# Use default production php.ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

CMD ["php-fpm"]

# BUILD DEV IMAGE
FROM base as dev

# Install xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Use default development php.ini
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Copy custom config
COPY ./.docker/conf/dev.ini /usr/local/etc/php/conf.d/php.ini

# Install composer
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# Create composer folder for user
RUN mkdir -p /var/www/.composer && chown -R $USER:$GROUP /var/www/.composer

# Create config folder for user
RUN mkdir -p /var/www/.config && chown -R $USER:$GROUP /var/www/.config

CMD ["php-fpm"]
