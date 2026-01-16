# use PHP 8.4
FROM php:8.4-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    pkg-config \
    zip \
    unzip \
    git \
    curl \
    libmariadb-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql \
    && docker-php-ext-enable gd

RUN pecl install redis && docker-php-ext-enable redis

# Set the working directory
COPY ../.. /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app/storage \
    && chmod -R 775 /var/www/app/storage

# install composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# copy composer.json to workdir & install dependencies
COPY ../../composer.json ./
RUN composer install

RUN chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
RUN chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache

# Set the default command to run php-fpm
CMD ["php-fpm"]
