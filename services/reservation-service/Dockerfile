FROM php:8.2-cli

# On crée un dossier de travail
WORKDIR /app

# On copie le code source
COPY src/ /app/src/

# On lance un serveur PHP interne simple sur le port 8001
CMD ["php", "-S", "0.0.0.0:8001", "-t", "/app/src"]

EXPOSE 8001