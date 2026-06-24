FROM php:8.2-apache

# Install ekstensi untuk CodeIgniter 4 dan PostgreSQL
RUN apt-get update && apt-get install -y libicu-dev libpq-dev git unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy semua file proyekmu ke dalam server
COPY . /var/www/html/
WORKDIR /var/www/html/

# Install dependensi CI4
RUN composer install --no-dev --optimize-autoloader

# Arahkan Apache ke folder /public milik CodeIgniter
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aktifkan mod_rewrite dan beri izin folder writable
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html/writable