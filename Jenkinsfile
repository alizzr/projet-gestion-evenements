pipeline {
    agent any

    stages {
        // --- 1. BUILD TOUS LES SERVICES ---
        stage('Build All Services') {
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml build user_service'
                    sh 'docker-compose -f docker-compose.jenkins.yml build event_service'
                    sh 'docker-compose -f docker-compose.jenkins.yml build reservation_service'
                    sh 'docker-compose -f docker-compose.jenkins.yml build notification_service'
                }
            }
        }

        // --- 2. TEST LARAVEL ---
        stage('Test User Service (Laravel)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d user_service'
                    sh 'sleep 10'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan config:clear'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan test'
                }
            }
        }

        // --- 3. TEST SYMFONY ---
        stage('Test Event Service (Symfony)') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d event_service'
                    sh 'sleep 10'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service php bin/console doctrine:schema:validate'
                }
            }
        }

        // --- 4. TEST RESERVATION (PHP Pur) ---
        stage('Test Reservation Service') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d reservation_service'
                    sh 'sleep 5'
                    // Test simple : on vérifie que le fichier index.php existe et est lisible
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T reservation_service ls -la /var/www/html/index.php'
                }
            }
        }

        // --- 5. TEST NOTIFICATION ---
        stage('Test Notification Service') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d notification_service'
                    sh 'sleep 5'
                    // Test simple : on vérifie que le fichier index.php existe
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T notification_service ls -la /var/www/html/index.php'
                }
            }
        }
    }
}