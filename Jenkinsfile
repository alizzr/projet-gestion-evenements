pipeline {
    agent any

    stages {
        // --- CONSTRUCTION GLOBALE ---
        stage('Build All Microservices') {
            steps {
                script {
                    echo "Construction des 4 microservices..."
                    sh 'docker-compose -f docker-compose.jenkins.yml build'
                }
            }
        }

        // --- SERVICE 1: LARAVEL ---
        stage('Test: User Service (Laravel)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml stop user_service && docker-compose -f docker-compose.jenkins.yml rm -f user_service' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d user_service'
                    sh 'sleep 10'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan config:clear'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan test'
                }
            }
        }

        // --- SERVICE 2: SYMFONY ---
        stage('Test: Event Service (Symfony)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml stop event_service && docker-compose -f docker-compose.jenkins.yml rm -f event_service' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d event_service'
                    sh 'sleep 10'
                    // Validation du schéma BDD
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service php bin/console doctrine:schema:validate'
                }
            }
        }

        // --- SERVICE 3: RÉSERVATION ---
        stage('Test: Reservation Service (PHP Native)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml stop reservation_service && docker-compose -f docker-compose.jenkins.yml rm -f reservation_service' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d reservation_service'
                    sh 'sleep 5'
                    // Vérification de syntaxe PHP (Lint)
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T reservation_service php -l /app/src/index.php'
                }
            }
        }

        // --- SERVICE 4: NOTIFICATION ---
        stage('Test: Notification Service (PHP Native)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml stop notification_service && docker-compose -f docker-compose.jenkins.yml rm -f notification_service' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d notification_service'
                    sh 'sleep 5'
                    // Vérification de syntaxe PHP (Lint)
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T notification_service php -l /app/src/index.php'
                }
            }
        }
    }
}