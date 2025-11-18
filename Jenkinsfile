pipeline {
    agent any

    stages {
        // --- 1. BUILD GLOBAL ---
        stage('Build All Services') {
            steps {
                script {
                    echo "Construction des 6 microservices (Back + Front + Gateway)..."
                    sh 'docker-compose -f docker-compose.jenkins.yml build'
                }
            }
        }

        // --- 2. TEST BACKEND (Laravel) ---
        stage('Test Backend: Laravel') {
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

        // --- 3. TEST BACKEND (Symfony) ---
        stage('Test Backend: Symfony') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d event_service'
                    sh 'sleep 10'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service php bin/console doctrine:schema:validate'
                }
            }
        }

        // --- 4. TEST PHP NATIFS ---
        stage('Test Backend: PHP Native Services') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d reservation_service notification_service'
                    sh 'sleep 5'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T reservation_service php -l /app/src/index.php'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T notification_service php -l /app/src/index.php'
                }
            }
        }

       // --- 5. TEST FRONTEND & GATEWAY ---
        stage('Test Frontend & Gateway') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d api_gateway frontend'
                    sh 'sleep 5'
                    // On vérifie juste que les conteneurs tournent
                    sh 'docker-compose -f docker-compose.jenkins.yml ps | grep "Up"'
                }
            }
        }
    }
}