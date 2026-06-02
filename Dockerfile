FROM php:8.2-apache

# 1. Install required Linux tools and PostgreSQL drivers
RUN apt-get update && apt-get install -y libpq-dev zip unzip git
RUN docker-php-ext-install pdo pdo_pgsql

# 2. Enable Apache rewrite module (Needed for Laravel routes)
RUN a2enmod rewrite

# 3. Change Apache's root directory to Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Set the working directory
WORKDIR /var/www/html

# 5. Copy the project files into the container
COPY . .

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Give Laravel permission to save files (like sessions and caches)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Command to run when the server starts
CMD php artisan migrate --force && apache2-foreground