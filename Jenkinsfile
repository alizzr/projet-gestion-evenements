pipeline {
    agent any

    stages {
        // 1. Construction
        stage('Build') {
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml build user_service'
                    sh 'docker-compose -f docker-compose.jenkins.yml build event_service'
                }
            }
        }

        // 2. Test Laravel
        stage('Test Laravel') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d user_service'
                    sh 'sleep 15' // Attente sécu BDD
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan config:clear'
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www user_service php artisan test'
                }
            }
        }

        // 3. Test Symfony
        stage('Test Symfony') {
            post { always { sh 'docker-compose -f docker-compose.jenkins.yml down' } }
            steps {
                script {
                    sh 'docker-compose -f docker-compose.jenkins.yml up -d event_service'
                    sh 'sleep 15' // Attente sécu BDD
                    // On vérifie juste que la BDD est accessible
                    sh 'docker-compose -f docker-compose.jenkins.yml exec -T --workdir /var/www event_service php bin/console doctrine:schema:validate'
                }
            }
        }
    }
}