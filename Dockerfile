FROM php:8.2-fpm

# Cài extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nginx config
COPY ./nginx/default.conf /etc/nginx/sites-available/default

WORKDIR /var/www/html

# Quyền thư mục
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
