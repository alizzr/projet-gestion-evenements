// Fichier: Jenkinsfile (FINAL Corrigé)
pipeline {
    agent any

    stages {

        // --- ÉTAPE 1: BUILD ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'environnement..."
                    sh 'docker-compose build'
                }
            }
        }

        // --- ÉTAPE 2: TEST & ANALYSIS (Corrigé) ---
        stage('Test & Analysis') {
            steps {
                script {
                    echo "Étape 2a: Lancement des tests..."
                    sh 'docker-compose exec -T user_service php artisan test'

                    echo "Étape 2b: Lancement de l'analyse SonarQube..."

                    withSonarQubeEnv('SonarQube') {
                        // On passe aux guillemets doubles """ pour que ${SONARQUBE_TOKEN} soit interprété
                        sh """
                        docker-compose exec -T user_service \
                        /opt/sonar-scanner/bin/sonar-scanner \
                        -Dsonar.host.url=http://sonarqube:9000 \
                        -Dsonar.login=${SONARQUBE_TOKEN} \
                        -Dsonar.sources=.
                        """
                        // J'ai aussi simplifié la commande pour l'instant
                    }
                }
            }
        }

        // --- ÉTAPE 3: RELEASE ---
        stage('Release') {
            steps {
                script {
                    echo "Étape 3: (Futur) Création et push de l'image de production..."
                }
            }
        }
    }
}