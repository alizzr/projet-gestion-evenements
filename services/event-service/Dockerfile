# Fichier: services/event-service/Dockerfile (CORRIGÉ - Ordre)
FROM php:8.2-fpm

ARG user=symfony
ARG uid=1001

# 1. Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip wget libpng-dev libonig-dev libxml2-dev libicu-dev

# 2. Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache intl

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Installer Sonar-Scanner
ENV SONAR_SCANNER_VERSION=4.7.0.2747
ENV SONAR_SCANNER_HOME=/opt/sonar-scanner
RUN mkdir -p /opt && \
    wget -q -O /tmp/sonar-scanner.zip "https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-${SONAR_SCANNER_VERSION}-linux.zip" && \
    unzip /tmp/sonar-scanner.zip -d /opt && \
    rm /tmp/sonar-scanner.zip && \
    mv /opt/sonar-scanner-${SONAR_SCANNER_VERSION}-linux ${SONAR_SCANNER_HOME} && \
    ln -s ${SONAR_SCANNER_HOME}/bin/sonar-scanner /usr/local/bin/sonar-scanner

# 5. Créer l'utilisateur
RUN adduser --shell /bin/bash --uid ${uid} --disabled-password --gecos "" ${user}

WORKDIR /var/www

USER root

# --- CORRECTION ---
# On copie D'ABORD juste composer.json et composer.lock
COPY composer.json composer.lock ./

# On installe les dépendances de base
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# ENSUITE on installe Doctrine (qui va modifier le composer.lock)
RUN composer require symfony/orm-pack --no-interaction --no-scripts

# ENFIN on copie le code source (y compris l'entité Event.php)
COPY . .

# On lance une dernière fois l'autoloader et les scripts post-install
# Maintenant que Doctrine est là, ça ne plantera pas
RUN composer dump-autoload --optimize
# ------------------

RUN chown -R ${user}:${user} .
USER ${user}

EXPOSE 9000
CMD ["php-fpm"]