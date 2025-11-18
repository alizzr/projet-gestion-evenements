# Fichier: services/frontend/Dockerfile (Version Légère)
FROM nginx:alpine

# On copie directement notre page d'accueil
COPY index.html /usr/share/nginx/html/index.html

# Configuration pour que React Router marche (si besoin plus tard)
# Mais pour l'instant ça suffit pour afficher l'interface
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]