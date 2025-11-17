// Fichier: Jenkinsfile (Mis à jour pour 2 services)
pipeline {
    agent any

    stages {
        
        // --- ÉTAPE 1: BUILD (Construit les deux) ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1a: Construction de l'image user_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml build user_service'
                    
                    echo "Étape 1b: Construction de l'image event_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml build event_service'
                }
            }
        }

        // --- ÉTAPE 2: TEST & ANALYSIS (Laravel) ---
        stage('Test & Analysis: user-service (Laravel)') {
            post {
                always {
                    script {
                        echo "Nettoyage du conteneur user_service..."
                        sh 'docker-compose -f docker-compose.jenkins.yml down'
                    }
                }
            }
            steps {
                script {
                    echo "Démarrage du conteneur user_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d user_service'
                    echo "Attente de 10s..."
                    sh 'sleep 10'

                    echo "Lancement des tests Laravel..."
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan test'
                    
                    echo "Lancement de l'analyse SonarQube (Laravel)..."
                    withCredentials([string(credentialsId: 'sonar-token', variable: 'SONAR_TOKEN_SECRET')]) {
                        sh """
                        docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service \
                        /opt/sonar-scanner/bin/sonar-scanner \
                        -Dsonar.host.url=http://sonarqube:9000 \
                        -Dsonar.login=${SONAR_TOKEN_SECRET} \
                        -Dsonar.projectKey=user-service \
                        -Dsonar.sources=.
                        """
                    }
                }
            }
        }

        // --- ÉTAPE 3: TEST & ANALYSIS (Symfony) ---
        stage('Test & Analysis: event-service (Symfony)') {
            post {
                always {
                    script {
                        echo "Nettoyage du conteneur event_service..."
                        sh 'docker-compose -f docker-compose.jenkins.yml down'
                    }
                }
            }
            steps {
                script {
                    echo "Démarrage du conteneur event_service..."
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d event_service'
                    echo "Attente de 10s..."
                    sh 'sleep 10'

                    echo "Lancement des tests Symfony (vérification BDD)..."
                    // On vérifie juste que le service tourne et peut voir la BDD
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service php bin/console doctrine:schema:validate'
                    
                    echo "Lancement de l'analyse SonarQube (Symfony)..."
                    withCredentials([string(credentialsId: 'sonar-token', variable: 'SONAR_TOKEN_SECRET')]) {
                        sh """
                        docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service \
                        /opt/sonar-scanner/bin/sonar-scanner \
                        -Dsonar.host.url=http://sonarqube:9000 \
                        -Dsonar.login=${SONAR_TOKEN_SECRET} \
                        -Dsonar.projectKey=event-service \
                        -Dsonar.sources=.
                        """
                    }
                }
            }
        }
    }
}