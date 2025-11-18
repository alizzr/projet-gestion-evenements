FROM php:8.2-cli

WORKDIR /app
COPY src/ /app/src/

# Serveur PHP interne sur le port 8002
CMD ["php", "-S", "0.0.0.0:8002", "-t", "/app/src"]

EXPOSE 8002