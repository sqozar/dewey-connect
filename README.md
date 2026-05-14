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

### 📌 Avant de commencer : Fork ou accès direct?

#### Cas 1 : Vous êtes contributeur SANS accès direct au repo (la majorité des cas)

Si vous n'avez pas les droits de push sur le repo principal, vous devez d'abord faire un **fork**:

##### Étape 1 : Forker le repository

1. Allez sur la page GitHub du projet
2. Cliquez sur le bouton **"Fork"** (en haut à droite)
3. GitHub crée une copie du repo sous votre compte

##### Étape 2 : Cloner votre fork en local

```bash
# Remplacez <votre-username> par votre nom d'utilisateur GitHub
git clone https://github.com/<votre-username>/dewey-connect.git
cd dewey-connect

# Ajouter le repo original en tant que "upstream" (pour rester à jour)
git remote add upstream https://github.com/original-owner/dewey-connect.git
```

**Explication:**
- `origin` = **votre copie** du projet (votre fork)
- `upstream` = **la copie officielle** du projet (le repo original)

##### Étape 3 : Mettre à jour votre fork avant de travailler

```bash
# Récupérer les derniers changements depuis le repo officiel
git fetch upstream

# Aller sur la branche main
git checkout main

# Fusionner les changements du repo officiel dans votre copie
git merge upstream/main

# Envoyer les mises à jour vers votre fork
git push origin main
```

**Explication simple:**
- Vous aviez laissé votre fork en retard? Ces commandes le rattrapent
- C'est comme dire: "OK, apporte-moi les derniers changements de l'original, mets-les dans ma copie, et sauvegarde-les sur GitHub"

#### Cas 2 : Vous êtes mainteneur AVEC accès direct au repo

Vous pouvez cloner directement et travailler sur le repo principal:

```bash
git clone https://github.com/original-owner/dewey-connect.git
cd dewey-connect
```

---

### ⚠️ Important : Déploiement automatique

**À chaque push sur `main` du repo principal, le déploiement automatique se déclenche!**

Donc ne poussez jamais directement sur `main`. Utilisez toujours une branche et les Pull Requests.

### Workflow correct

#### 1. Créer une branche pour votre travail

```bash
# Vérifiez que vous êtes sur main et à jour
git checkout main
git pull origin main

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

# Aller sur GitHub et créer une pull request
# → Tests automatiques s'exécutent
```

#### 4. Attendre la validation de la pull request

- Les mainteneurs revoit votre code
- Les tests automatiques s'exécutent
- Une fois approuvée, la pull request est fusionnée sur `main`

**Note:** Si vous travaillez sur un fork, votre pull request sera créée vers le repo original.

#### 5. Merger avec main (MAINTENEURS UNIQUEMENT)

⚠️ **Réservé aux personnes avec accès direct au repo**

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

#### 6. Nettoyer votre branche locale

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
