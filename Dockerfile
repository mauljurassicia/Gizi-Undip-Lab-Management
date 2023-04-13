FROM php:7.4

# Set workdir
WORKDIR /app

# Copy all files
COPY . .

## View files
RUN ls -la

# Update package
RUN apt-get update -yqq && apt-get install gnupg -yqq

RUN apt-get install git libzip-dev libcurl4-gnutls-dev libicu-dev libmcrypt-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev unixodbc-dev libpq-dev libpcre3-dev libtidy-dev libonig-dev -yqq

# install ext
RUN docker-php-ext-install pdo_mysql mbstring curl json intl gd xml zip bz2 opcache

RUN php composer.phar install || true
RUN php artisan key:generate || true
RUN php artisan optimize:clear || true
RUN php artisan storage:link || true

# Run App
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
