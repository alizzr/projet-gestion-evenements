// Fichier: Jenkinsfile (FINAL avec SonarQube)
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

        // --- ÉTAPE 2: TEST & ANALYSIS (Modifié) ---
        stage('Test & Analysis') {
            steps {
                script {
                    echo "Étape 2a: Lancement des tests..."
                    sh 'docker-compose exec -T user_service php artisan test'
                    
                    echo "Étape 2b: Lancement de l'analyse SonarQube..."
                    
                    // Indique à Jenkins quel serveur SonarQube utiliser
                    withSonarQubeEnv('SonarQube') {
                        // Exécute le scanner SonarQube à l'intérieur du conteneur Laravel
                        // C'est une commande complexe, mais elle dit :
                        // "Exécute le scanner, connecte-toi avec le token que nous avons sauvegardé (SONARQUBE_TOKEN),
                        // et envoie les résultats au serveur SonarQube."
                        sh '''
                        docker-compose exec -T user_service \
                        sonar-scanner \
                        -Dsonar.host.url=http://sonarqube:9000 \
                        -Dsonar.login=$SONARQUBE_TOKEN \
                        -Dsonar.sources=. \
                        -Dsonar.php.coverage.reportPaths=tests/coverage/clover.xml \
                        -Dsonar.php.cs.reportPaths=tests/logs/phpcs.xml
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