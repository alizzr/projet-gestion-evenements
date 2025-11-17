# Fichier: services/event-service/Dockerfile (FINAL - avec pdo_mysql)
FROM php:8.2-fpm

ARG user=symfony
ARG uid=1001

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    wget \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev

# --- CORRECTION ICI ---
# Installer les extensions PHP (ajout de pdo_mysql)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache intl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Sonar-Scanner
ENV SONAR_SCANNER_VERSION=4.7.0.2747
ENV SONAR_SCANNER_HOME=/opt/sonar-scanner
RUN mkdir -p /opt && \
    wget -q -O /tmp/sonar-scanner.zip "https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-${SONAR_SCANNER_VERSION}-linux.zip" && \
    unzip /tmp/sonar-scanner.zip -d /opt && \
    rm /tmp/sonar-scanner.zip && \
    mv /opt/sonar-scanner-${SONAR_SCANNER_VERSION}-linux ${SONAR_SCANNER_HOME} && \
    ln -s ${SONAR_SCANNER_HOME}/bin/sonar-scanner /usr/local/bin/sonar-scanner

# Créer un utilisateur
RUN adduser --shell /bin/bash --uid ${uid} --disabled-password --gecos "" ${user}

WORKDIR /var/www

USER root
# Copier tout le code
COPY . .

# Installer les dépendances (composer.json)
RUN composer install --no-interaction --optimize-autoloader

# Installer Doctrine
RUN composer require symfony/orm-pack --no-interaction

# Permissions
RUN chown -R ${user}:${user} .
USER ${user}

EXPOSE 9000
CMD ["php-fpm"]