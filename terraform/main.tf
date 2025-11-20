terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 4.16"
    }
  }
  required_version = ">= 1.2.0"
}

provider "aws" {
  region  = "us-east-1" # Région standard USA (souvent moins chère/par défaut)
}

# 1. Création du Groupe de Sécurité (Le Pare-feu)
resource "aws_security_group" "event_app_sg" {
  name        = "event_app_sg"
  description = "Allow Web and SSH traffic"

  # SSH (Pour vous connecter)
  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # Frontend (React)
  ingress {
    from_port   = 3000
    to_port     = 3000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # API Gateway
  ingress {
    from_port   = 8081
    to_port     = 8081
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # Jenkins (Si besoin)
  ingress {
    from_port   = 8080
    to_port     = 8080
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # Sortie (Internet illimité pour le serveur)
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# 2. Création du Serveur (Instance EC2)
resource "aws_instance" "app_server" {
  ami           = "ami-053b0d53c279acc90" # Ubuntu 22.04 LTS (us-east-1)
  instance_type = "t2.medium" # t2.medium recommandé pour Jenkins+Apps (t2.micro risque d'être juste)
  
  vpc_security_group_ids = [aws_security_group.event_app_sg.id]
  key_name = "ma-cle-ssh" # Remplacer par le nom de votre clé AWS si vous en avez une

  tags = {
    Name = "Projet-Gestion-Evenements"
  }

  # 3. Script d'installation automatique (User Data)
  # Ce script s'exécute tout seul au premier démarrage du serveur
  user_data = <<-EOF
              #!/bin/bash
              # Mettre à jour le système
              sudo apt-get update -y
              
              # Installer Docker et Git
              sudo apt-get install -y docker.io git curl
              
              # Installer Docker Compose
              sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
              sudo chmod +x /usr/local/bin/docker-compose
              
              # Démarrer Docker
              sudo systemctl start docker
              sudo systemctl enable docker
              
              # Ajouter l'utilisateur par défaut au groupe Docker
              sudo usermod -aG docker ubuntu
              
              # Cloner le projet (Remplacez par VOTRE lien GitHub)
              cd /home/ubuntu
              git clone https://github.com/alizzr/projet-gestion-evenements.git
              cd projet-gestion-evenements
              
              # Lancer l'application (sans Jenkins pour économiser la RAM sur le Cloud, ou avec si t2.medium)
              # On lance juste l'appli pour la démo
              sudo docker-compose up -d frontend api_gateway user_service event_service reservation_service notification_service db_mysql
              
              # Initialisation des données
              sleep 30
              sudo docker-compose exec -T user_service php artisan migrate --force
              sudo docker-compose exec -T event_service composer require symfony/orm-pack --no-scripts
              sudo docker-compose exec -T -e DATABASE_URL="mysql://root:root_password@db_mysql:3306/db_events?serverVersion=8.0" event_service php bin/console doctrine:schema:update --force
              EOF
}

# 4. Afficher l'IP publique à la fin
output "public_ip" {
  value = aws_instance.app_server.public_ip
  description = "L'adresse IP publique du serveur"
}