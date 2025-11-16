// Fichier: Jenkinsfile (FINAL Corrigé pour le Token)
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

                    // Cette étape injecte la variable $SONAR_AUTH_TOKEN
                    withSonarQubeEnv('SonarQube') {
                        // On passe aux simples quotes ''' pour que Groovy laisse le $ tranquille
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