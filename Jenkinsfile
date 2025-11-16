// Fichier: Jenkinsfile
pipeline {
    agent any // Dit à Jenkins d'exécuter cela sur n'importe quel agent disponible

    stages {

        // --- ÉTAPE 1: BUILD (Construction) ---
        stage('Build') {
            steps {
                script {
                    echo "Étape 1: Construction de l'environnement..."
                    // Jenkins va construire les images Docker
                    sh 'docker-compose build'
                }
            }
        }

        // --- ÉTAPE 2: TEST (Test Intelligence) ---
        stage('Test') {
            steps {
                script {
                    echo "Étape 2: Lancement des tests..."
                    // Jenkins lance les services (BDD, app) en arrière-plan
                    sh 'docker-compose up -d'

                    // Jenkins exécute les tests (exactement comme vous l'avez fait)
                    echo "Lancement des tests unitaires et feature..."
                    sh 'docker-compose exec user_service php artisan test'

                    echo "Lancement de l'analyse SonarQube..."
                    // C'est ici que nous ajouterons l'étape SonarQube
                }
            }
        }

        // --- ÉTAPE 3: CLEANUP (Nettoyage) ---
        stage('Cleanup') {
            steps {
                script {
                    echo "Étape 3: Arrêt de l'environnement de test..."
                    // Jenkins arrête et supprime les conteneurs de test
                    sh 'docker-compose down'
                }
            }
        }

        // --- ÉTAPE 4: RELEASE (Orchestration) ---
        stage('Release') {
            steps {
                script {
                    echo "Étape 4: (Futur) Création et push de l'image de production..."
                }
            }
        }
    }
}