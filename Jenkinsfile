// Fichier: Jenkinsfile (CORRIGÉ)
pipeline {
    agent any

    stages {
        
        // --- ÉTAPE 1: BUILD (C'est OK) ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'environnement..."
                    // C'est bien de reconstruire les images
                    sh 'docker-compose build'
                }
            }
        }

        // --- ÉTAPE 2: TEST (Modifié) ---
        stage('Test') {
            steps {
                script {
                    echo "Étape 2: Lancement des tests..."
                    
                    // ON SUPPRIME LA LIGNE 'docker-compose up -d'
                    // Les services tournent déjà !
                    
                    echo "Lancement des tests unitaires et feature..."
                    // On exécute juste le test sur le service qui tourne
                    sh 'docker-compose exec user_service php artisan test'
                    
                    echo "Lancement de l'analyse SonarQube..."
                    // (futur)
                }
            }
        }
        
        // --- ÉTAPE 3: CLEANUP (Supprimé) ---
        // ON SUPPRIME TOUTE L'ÉTAPE CLEANUP
        // 'docker-compose down' arrêterait Jenkins lui-même !
        
        // --- ÉTAPE 4: RELEASE (C'est OK) ---
        stage('Release') {
            steps {
                script {
                    echo "Étape 4: (Futur) Création et push de l'image de production..."
                }
            }
        }
    }
}