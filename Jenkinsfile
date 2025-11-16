// Fichier: Jenkinsfile (FINAL - Logique de mise à jour)
pipeline {
    agent any

    stages {
        
        // --- ÉTAPE 1: BUILD ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'image user_service..."
                    // Construit la nouvelle image à partir du code
                    sh 'docker-compose build user_service'
                }
            }
        }

        // --- ÉTAPE 2: TEST & ANALYSIS ---
        stage('Test & Analysis') {
            steps {
                script {
                    echo "Étape 2a: Mise à jour du conteneur user_service..."
                    // Force le conteneur 'user_service' à redémarrer
                    // en utilisant la nouvelle image de l'étape 1.
                    // --no-deps garantit qu'il ne touche pas aux BDD.
                    sh 'docker-compose up -d --no-deps user_service'

                    echo "Attente de 10s que le service Laravel redémarre..."
                    sh 'sleep 10'

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
        
        // --- ÉTAPE 3: RELEASE (Pas de post-cleanup) ---
        stage('Release') {
            steps {
                script {
                    echo "Étape 3: (Futur) Création et push de l'image de production..."
                }
            }
        }
    }
}