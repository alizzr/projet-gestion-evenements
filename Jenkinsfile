// Fichier: Jenkinsfile (CORRIGÉ AVEC -T)
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

        // --- ÉTAPE 2: TEST (Modifié) ---
        stage('Test') {
            steps {
                script {
                    echo "Étape 2: Lancement des tests..."
                    echo "Lancement des tests unitaires et feature..."
                    
                    // Ajout du drapeau -T pour désactiver le TTY
                    sh 'docker-compose exec -T user_service php artisan test'
                    
                    echo "Lancement de l'analyse SonarQube..."
                    // (futur)
                }
            }
        }
        
        // --- ÉTAPE 4: RELEASE ---
        stage('Release') {
            steps {
                script {
                    echo "Étape 4: (Futur) Création et push de l'image de production..."
                }
            }
        }
    }
}