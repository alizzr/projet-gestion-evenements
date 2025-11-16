// Fichier: Jenkinsfile (FINAL - Gestion du cycle de vie)
pipeline {
    agent any

    stages {

        // --- ÉTAPE 1: BUILD ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'image user_service..."
                    // On construit spécifiquement le service qui nous intéresse
                    sh 'docker-compose build user_service'
                }
            }
        }

        // --- ÉTAPE 2: TEST & ANALYSIS (Corrigé) ---
        stage('Test & Analysis') {
            steps {
                script {
                    echo "Étape 2a: Redémarrage du conteneur user_service..."
                    // On arrête l'ancien conteneur (s'il existe)
                    sh 'docker-compose stop user_service'
                    // On le supprime
                    sh 'docker-compose rm -f user_service'
                    // On démarre le nouveau (basé sur l'image de l'Étape 1)
                    // Il va automatiquement se lier aux autres services (DB, etc.)
                    sh 'docker-compose up -d user_service'

                    echo "Attente de 10s que le service Laravel démarre..."
                    sh 'sleep 10' // Laisse le temps au conteneur de démarrer

                    echo "Étape 2b: Lancement des tests..."
                    sh 'docker-compose exec -T user_service php artisan test'

                    echo "Étape 2c: Lancement de l'analyse SonarQube..."
                    withSonarQubeEnv('SonarQube') {
                        sh '''
                        docker-compose exec -T user_service \
                        /opt/sonar-scanner/bin/sonar-scanner \
                        -Dsonar.host.url=http://sonarqube:9000 \
                        -Dsonar.login=$SONAR_AUTH_TOKEN \
                        -Dsonar.sources=.
                        '''
                    }
                }
            }
        }
    }
}