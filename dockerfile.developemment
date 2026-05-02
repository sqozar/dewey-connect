FROM php:8.3-fpm-alpine

# Définir le répertoire de travail
WORKDIR /var/www

# Installer les dépendances système et extensions PHP nécessaires
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    icu-dev \
    icu-libs \
    postgresql-client \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    intl \
    opcache \
    && apk del --no-cache libpq-dev icu-dev

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-interaction --no-progress

# Créer les répertoires de cache et logs avec les bonnes permissions
RUN mkdir -p var/cache var/log \
    && chmod -R 777 var/

# Copier la configuration PHP
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configurer les opcache
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini" \
    && echo "opcache.memory_consumption=256" >> "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini" \
    && echo "opcache.interned_strings_buffer=16" >> "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini" \
    && echo "opcache.max_accelerated_files=20000" >> "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini"

# Exposer le port (FPM écoute sur 9000)
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]
