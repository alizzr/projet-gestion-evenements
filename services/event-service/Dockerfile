# Fichier: services/event-service/Dockerfile (Corrigé)
FROM php:8.2-fpm

ARG user=symfony
ARG uid=1001

# ... (toutes les installations : apt-get, extensions, sonar-scanner...) ...

# Créer un utilisateur
RUN adduser --shell /bin/bash --uid ${uid} --disabled-password --gecos "" ${user}

WORKDIR /var/www

USER root
# Copier tout le code
COPY . .

# Installer les dépendances (composer.json)
RUN composer install --no-interaction --optimize-autoloader

# --- AJOUTER CETTE LIGNE ---
# Installer Doctrine (pour que la commande 'doctrine:schema:validate' existe)
RUN composer require symfony/orm-pack --no-interaction
# --- FIN DE L'AJOUT ---

# Permissions (Symfony écrit dans var/)
RUN chown -R ${user}:${user} .
USER ${user}

EXPOSE 9000
CMD ["php-fpm"]