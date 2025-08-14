FROM php:8.2-apache

RUN apt-get update && apt-get install -y git unzip libpq-dev \ 
    && docker-php-ext-install pdo pdo_mysql     && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Serve /public as DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf     && printf "<Directory /var/www/public>\nAllowOverride All\nRequire all granted\nDirectoryIndex index.html index.php\n</Directory>\n" >> /etc/apache2/apache2.conf

COPY . /var/www

RUN composer install --no-interaction --prefer-dist --optimize-autoloader || true

EXPOSE 80
