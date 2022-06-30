pipeline {
    environment {
        registry = 'docker.alfabeta.co.id/agms'
        registryCredential = 'docker-registry-alfabeta'
        dockerImage = ''
    }

    agent any

    stages {
        stage('Build') {
            steps {
                script {
                    dockerImage = docker.build registry
                }
            }
        }
        stage('Deploy image') {
            steps {
                script {
                    docker.withRegistry('https://docker.alfabeta.co.id', registryCredential) {
                        dockerImage.push(env.BRANCH_NAME)
                        dockerImage.push()
                    }
                }
            }
        }

        stage('Remove docker image') {
            steps{
                sh "docker rmi $registry"
                sh "docker rmi $registry:$env.BRANCH_NAME"
                sh "docker image prune -a -f"
            }
        }
    }
}
