# Fichier: services/user-service/Dockerfile (FINAL - avec composer install)

# Utiliser une image PHP 8.2 officielle
FROM php:8.2-fpm

# Arguments
ARG user=laravel
ARG uid=1000

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    wget

# Installer les extensions PHP requises par Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- Installation de Sonar-Scanner ---
ENV SONAR_SCANNER_VERSION=4.7.0.2747
ENV SONAR_SCANNER_HOME=/opt/sonar-scanner
RUN mkdir -p /opt && \
    wget -q -O /tmp/sonar-scanner.zip "https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-${SONAR_SCANNER_VERSION}-linux.zip" && \
    unzip /tmp/sonar-scanner.zip -d /opt && \
    rm /tmp/sonar-scanner.zip && \
    mv /opt/sonar-scanner-${SONAR_SCANNER_VERSION}-linux ${SONAR_SCANNER_HOME} && \
    ln -s ${SONAR_SCANNER_HOME}/bin/sonar-scanner /usr/local/bin/sonar-scanner

# Créer un utilisateur pour l'application
RUN adduser --shell /bin/bash --uid ${uid} --disabled-password --gecos "" ${user}

# Définir le répertoire de travail
WORKDIR /var/www

# --- CORRECTION FINALE ---
# On repasse en root pour les permissions
USER root

# Copier les fichiers de dépendances D'ABORD (pour le cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances (en tant que root pour avoir les droits)
# --no-dev : N'installe pas les paquets de débogage (plus rapide)
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copier tout le reste du code
COPY . .

# Donner la propriété de tous les fichiers à notre utilisateur
RUN chown -R ${user}:${user} .

# Rebasculer à l'utilisateur non-root
USER ${user}
# --- FIN DE LA CORRECTION ---

# Exposer le port 9000 pour php-fpm
EXPOSE 9000
CMD ["php-fpm"]
