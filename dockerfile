FROM php:8.2-cli

WORKDIR /var/www

# Installer dépendances
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Variables de production
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Copier projet
COPY . .

# Installer dépendances
RUN composer install --no-dev --optimize-autoloader

# Exposer port Render
EXPOSE 10000

# Lancer serveur PHP
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
