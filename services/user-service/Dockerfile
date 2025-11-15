# Fichier: services/user-service/Dockerfile

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
    unzip

# Fichier: services/user-service/Dockerfile

# ... (gardez tout ce qui est au-dessus) ...

# Installer les extensions PHP requises par Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- DÉBUT DU BLOC À CORRIGER ---

# Créer un utilisateur pour l'application
RUN adduser --shell /bin/bash --uid ${uid} --disabled-password --gecos "" ${user}

# Basculer vers cet utilisateur
USER ${user}

# --- FIN DU BLOC À CORRIGER ---

# Définir le répertoire de travail
WORKDIR /var/www

# Exposer le port 9000 pour php-fpm
EXPOSE 9000
CMD ["php-fpm"]