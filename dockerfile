FROM php:8.2-fpm

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

ENV APP_ENV=prod
ENV APP_DEBUG=0

# Copier le projet
COPY . .

# Installer les dépendances (--no-scripts évite cache:clear qui nécessite la DB)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Permissions
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]
