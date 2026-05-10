# Dewey Connect - Guide complet

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Installation locale](#installation-locale)
4. [Déploiement](#déploiement)

---

## Vue d'ensemble

**Dewey Connect** est une application web de covoiturage développé avec **Symfony 6.4**, **PostgreSQL**, et **Docker**.

### Technologies principales

- **Framework**: Symfony 6.4.x
- **Langage**: PHP 8.3
- **Base de données**: PostgreSQL 16
- **Conteneurisation**: Docker & Docker Compose
- **Frontend**: Twig
- **ORM**: Doctrine
- **Tests**: PHPUnit
- **CI/CD**: GitHub Actions

### Fonctionnalités principales

- Gestion des utilisateurs
- Gestion des trajets
- Gestion des réservations associées aux trajets existants
- Interface utilisateur responsive

---

## Prérequis

### Pour le développement local

- **PHP 8.3** ou supérieur
- **Composer** (gestionnaire de dépendances PHP)
- **PostgreSQL 16** (local ou via Docker)
- **Docker** et **Docker Compose**
- **Node.js** (nécessaire pour la gestion du Sass)

### Pour le déploiement

- **Docker** et **Docker Compose**
- **Nginx** ou tout serveur web compatible
- Accès à un serveur (Linux recommandé)

---

## Installation locale

### Avec Docker

```bash
# 1. Cloner le projet
git clone <url-du-repo> dewey-connect
cd dewey-connect

# 2. Démarrer les conteneurs
docker-compose up -d

# 3. Installer les dépendances PHP
docker exec dewey-connect-php composer install

# 4. Créer la base de données
docker exec dewey-connect-php php bin/console doctrine:database:create

# 5. Exécuter les migrations
docker exec dewey-connect-php php bin/console doctrine:migrations:migrate

# 6. Accéder à l'application
http://localhost:8000
```

**Services disponibles :**
- Application : http://localhost:8000
- PostgreSQL : localhost:5432 (utilisateur: `app`, mot de passe: voir `.env`)

### Fichier .env.local (pour développement local)

```env
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=votre-clé-secrète-ici

DATABASE_URL="postgresql://app:password@127.0.0.1:5432/dewey_connect?serverVersion=16&charset=utf8"
```

---

## Déploiement

### Déploiement avec Docker

#### 1. Préparer le projet pour la production

```bash
# Sur votre machine locale
composer install --optimize-autoloader --no-dev

# Générer les clés secrètes
php bin/console secrets:generate-keys

# Compiler l'environnement
composer dump-env prod
```

#### 2. Pousser sur le serveur

```bash
git add .
git commit -m "Préparation pour la production"
git push origin main

# Sur le serveur :
cd /var/www/dewey-connect
git pull origin main
```

#### 3. Déployer avec Docker sur le serveur

```bash
# Éditer les variables d'environnement
nano .env.local

# Démarrer les conteneurs
docker-compose -f docker-compose.yml up -d

# Installer les dépendances
docker exec dewey-connect-php composer install --optimize-autoloader --no-dev

# Créer la base de données (si première fois)
docker exec dewey-connect-php php bin/console doctrine:database:create

# Exécuter les migrations
docker exec dewey-connect-php php bin/console doctrine:migrations:migrate

# Nettoyer le cache
docker exec dewey-connect-php php bin/console cache:clear --env=prod
```

### Avec CI/CD GitHub Actions

Le projet inclut `.github/workflows/ci.yml` qui execute automatiquement les tests à chaque push.

Pour activer le déploiement automatique :

1. Ajoutez des secrets GitHub :
   - `SSH_KEY` : clé SSH privée pour accéder au serveur
   - `SSH_HOST` : adresse du serveur
   - `SSH_USER` : utilisateur SSH

2. Créez ``.github/workflows/deploy.yml`` :

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v4
      
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/dewey-connect
            git pull origin main
            docker-compose up -d
            docker exec dewey-connect-php composer install --optimize-autoloader --no-dev
            docker exec dewey-connect-php php bin/console doctrine:migrations:migrate
            docker exec dewey-connect-php php bin/console cache:clear --env=prod
```

---
