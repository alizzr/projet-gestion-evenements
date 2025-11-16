// Fichier: Jenkinsfile (FINAL - Gestion complète du cycle de vie)
pipeline {
    agent any

    stages {
        
        // --- ÉTAPE 1: BUILD ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'image user_service..."
                    sh 'docker-compose build user_service'
                }
            }
        }

        // --- ÉTAPE 2: TEST & ANALYSIS ---
        stage('Test & Analysis') {
            // "post" s'assure que le cleanup se lance MÊME SI les tests échouent
            post {
                always {
                    script {
                        echo "Étape 2-POST: Arrêt et nettoyage du conteneur de test..."
                        sh 'docker-compose stop user_service'
                        sh 'docker-compose rm -f user_service'
                    }
                }
            }
            steps {
                script {
                    echo "Étape 2a: Démarrage du conteneur user_service..."
                    // On démarre le nouveau conteneur à partir de l'image construite
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