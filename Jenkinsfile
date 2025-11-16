// Fichier: Jenkinsfile (FINAL - Logique séparée)
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

        // --- ÉTAPE 2: TEST & ANALYSIS ---
        stage('Test & Analysis') {
            post {
                always {
                    script {
                        echo "Étape 2-POST: Arrêt et nettoyage du conteneur de test..."
                        // 'down' ne détruira que le service défini dans ce fichier
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
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T user_service php artisan test'

                    echo "Étape 2c: Lancement de l'analyse SonarQube..."
                    withSonarQubeEnv('SonarQube') {
                        sh '''
                        docker-compose -f docker-compose.jenkins.yml exec -T user_service \
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