# Fichier: jenkins-server/Dockerfile

# Partir de l'image officielle de Jenkins
FROM jenkins/jenkins:lts-jdk11

# Basculer en 'root' pour installer des outils
USER root

# 1. Mettre à jour apt et installer docker-compose (V1) directement
RUN apt-get update && apt-get install -y docker-compose

# 2. (LIGNE AJOUTÉE) 
#    Ajouter l'utilisateur 'jenkins' au groupe 'docker' pour lui donner
#    la permission d'utiliser le socket Docker.
RUN usermod -aG docker jenkins

# Rebasculer vers l'utilisateur 'jenkins' (meilleure pratique)
USER jenkins