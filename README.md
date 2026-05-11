# Dewey Connect - Guide complet

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Installation locale](#installation-locale)
4. [Workflow Git et déploiement](#workflow-git-et-déploiement)

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

- **Docker** et **Docker Compose**
- **Node.js** (nécessaire pour la gestion du Sass)
- **Git** pour le versioning

Le déploiement en production est **automatique** via GitHub Actions et Render.

---

## Installation locale

### Avec Docker

```bash
# 1. Cloner le projet
git clone <url-du-repo> dewey-connect
cd dewey-connect

# 2. Lancer ET builder en même temps
docker-compose up -d --build

# 3. Initialiser la base de données
docker exec symfony_app php bin/console doctrine:database:create
docker exec symfony_app php bin/console doctrine:migrations:migrate

# 4. Accéder à l'application
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

# Pour générer une clé sécurisée tapper la commande "openssl rand -hex 32" dans votre terminal

DATABASE_URL="postgresql://app:password@127.0.0.1:5432/dewey_connect?serverVersion=16&charset=utf8"
```

---

## Workflow Git et déploiement

### ⚠️ Important : Déploiement automatique

**À chaque push sur `main`, le déploiement automatique se déclenche!**

Donc ne poussez jamais directement sur `main`. Utilisez toujours une branche.

### Workflow correct

#### 1. Créer une branche pour votre travail

```bash
# Créer une branche et se positionner dessus
git checkout -b feature/votre-feature
```

#### 2. Travailler et committer

```bash
# Faire vos modifications...

# Ajouter et committer
git add .
git commit -m "description claire de la modification"
```

#### 3. Pousser votre branche

```bash
# Envoyer votre branche sur le serveur Git
git push origin feature/votre-feature

# Aller sur GitHub et créer une Pull Request (PR)
# → Tests automatiques s'exécutent
```

#### 4. Merger avec main (ATTENTION: déclenche le déploiement)

```bash
# Se positionner sur main
git checkout main

# Récupérer les derniers changements
git pull origin main

# Fusionner votre branche
git merge feature/votre-feature

# Pousser sur main
git push origin main

# ⚡ AUTOMATIQUEMENT: Déploiement en production!
# (GitHub Actions se déclenche)
```

#### 5. Nettoyer votre branche locale

```bash
# Supprimer la branche locale (optionnel)
git branch -d feature/votre-feature
```

### Statut de déploiement

Vous pouvez voir l'état du déploiement:
- **GitHub** → Onglet "Actions" pour voir les workflows
- **Render** → Dashboard pour voir les logs de déploiement

À chaque push sur `main`:

1. ✅ Tests automatiques s'exécutent
2. ✅ Image Docker est construite
3. ✅ Déploiement sur Render
4. ✅ Application redémarrée en production

**Pas besoin de commandes manuelles!**

---
