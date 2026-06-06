FROM php:8.4-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpq-dev \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN echo 'upload_max_filesize=25M' > /usr/local/etc/php/conf.d/uploads.ini \
    && echo 'post_max_size=30M' >> /usr/local/etc/php/conf.d/uploads.ini

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN mkdir -p database \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    storage/app/public \
    bootstrap/cache

RUN touch database/database.sqlite

RUN chown -R www-data:www-data storage bootstrap/cache database
RUN chmod -R 775 storage bootstrap/cache database

RUN a2enmod rewrite

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD php artisan config:clear && php artisan storage:link || true && php artisan migrate --force --seed && apache2-foreground
