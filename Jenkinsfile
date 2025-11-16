// Fichier: Jenkinsfile (FINAL - Sans SonarQube)
pipeline {
    agent any

    stages {
        
        // --- ÉTAPE 1: BUILD ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'image de test user_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml build user_service'
                }
            }
        }

        // --- ÉTAPE 2: TEST ---
        stage('Test') {
            post {
                always {
                    script {
                        echo "Étape 2-POST: Arrêt et nettoyage du conteneur de test..."
                        sh 'docker-compose -f docker-compose.jenkins.yml down'
                    }
                }
            }
            steps {
                script {
                    echo "Étape 2a: Démarrage du conteneur de test user_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d user_service'

                    echo "Attente de 10s que le service Laravel démarre..."
                    sh 'sleep 10'

                    echo "Étape 2b: Lancement des tests..."
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan test'
                    
                    // --- ÉTAPE SONARQUBE SUPPRIMÉE ---
                }
            }
        }
    }
}