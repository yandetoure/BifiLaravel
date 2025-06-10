# Guide d'installation - Bifi

## Description

Bifi est une application Laravel moderne pour la gestion et le paiement de factures CMA. Elle permet aux clients de soumettre leurs factures (avec upload automatique OCR ou saisie manuelle) et aux agents de valider et traiter les paiements.

## Fonctionnalités principales

- **Page d'accueil publique** : Présentation de l'application et invitation au paiement
- **Upload de facture avec OCR** : Extraction automatique des données de facture
- **Saisie manuelle** : Formulaire pour saisir les informations manuellement
- **Gestion des rôles** : Client, Agent, Superviseur
- **Tableaux de bord** : Différentes vues selon le rôle utilisateur
- **Suivi des statuts** : En attente, Confirmée, Payée, Annulée
- **Interface moderne** : Design responsive avec Bootstrap et Tailwind CSS

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- Base de données (MySQL, PostgreSQL, SQLite)
- Tesseract OCR (pour l'extraction automatique)

## Installation

### 1. Cloner et installer les dépendances

```bash
# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install
```

### 2. Configuration de l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configuration de la base de données

Éditer le fichier `.env` avec vos paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bifi
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Exécuter les migrations et seeders

```bash
# Créer les tables
php artisan migrate

# Insérer les données de test
php artisan db:seed
```

### 5. Configurer le stockage

```bash
# Créer le lien symbolique pour le stockage public
php artisan storage:link

# Créer les dossiers nécessaires
mkdir -p storage/app/public/bills
mkdir -p storage/app/public/companies
chmod -R 755 storage
```

### 6. Compiler les assets

```bash
# Développement
npm run dev

# Production
npm run build
```

### 7. Démarrer l'application

```bash
# Démarrer le serveur de développement
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## Comptes de test

Après avoir exécuté les seeders, vous aurez accès à ces comptes :

### Administrateur/Superviseur
- **Email** : `admin@bifi.com`
- **Mot de passe** : `password`
- **Rôle** : Superviseur (accès complet)

### Agents
- **Email** : `agent1@bifi.com` ou `agent2@bifi.com`
- **Mot de passe** : `password`
- **Rôle** : Agent (validation des factures)

### Clients
- **Email** : `client1@example.com`, `client2@example.com`, `client3@example.com`
- **Mot de passe** : `password`
- **Rôle** : Client (soumission de factures)

## Utilisation

### Pour les clients

1. **Page d'accueil** : Présentation de l'application
2. **Payer une facture** : 
   - Option 1 : Upload d'image avec extraction OCR automatique
   - Option 2 : Saisie manuelle des informations
3. **Création de compte** (optionnel) : Pour suivre ses factures
4. **Tableau de bord** : Historique et suivi des factures

### Pour les agents/superviseurs

1. **Connexion** avec un compte agent ou superviseur
2. **Tableau de bord agent** : 
   - Vue d'ensemble des factures
   - Statistiques par statut
   - Filtres et recherche
3. **Gestion des factures** :
   - Voir les détails
   - Confirmer les factures en attente
   - Annuler avec motif
   - Traiter les paiements

### Flux de traitement

1. **Client** soumet une facture → Statut "En attente"
2. **Agent** vérifie et confirme → Statut "Confirmée"
3. **Agent** traite le paiement → Statut "Payée"
4. **Client** reçoit un reçu

## Configuration OCR (Optionnel)

Pour utiliser la fonctionnalité d'extraction automatique de données :

### Installation Tesseract

```bash
# Ubuntu/Debian
sudo apt-get install tesseract-ocr tesseract-ocr-fra

# macOS
brew install tesseract tesseract-lang

# Windows
# Télécharger depuis https://github.com/UB-Mannheim/tesseract/wiki
```

### Configuration Laravel

Dans `.env`, ajouter :

```env
TESSERACT_OCR_EXECUTABLE=/usr/bin/tesseract
```

## Structure des données

### Entreprises partenaires
- CMA EDL (Électricité du Laos)
- CMA Télécom Laos
- CMA Distribution d'Eau
- CMA Services Urbains
- CMA Gaz & Énergie
- CMA Transport Public

### Statuts des factures
- **pending** : En attente de validation
- **confirmed** : Confirmée par un agent
- **paid** : Payée
- **cancelled** : Annulée (avec motif)

## Développement

### Structure du projet

```
app/
├── Http/Controllers/
│   ├── HomeController.php       # Page d'accueil et tableaux de bord
│   ├── BillController.php       # Gestion des factures
│   ├── PaymentController.php    # Gestion des paiements
│   └── OcrController.php        # Extraction OCR
├── Models/
│   ├── User.php                 # Utilisateurs avec rôles
│   ├── Bill.php                 # Factures
│   ├── Company.php              # Entreprises partenaires
│   └── Payment.php              # Paiements
resources/views/
├── home.blade.php               # Page d'accueil publique
├── layouts/app.blade.php        # Layout principal
├── bills/                       # Vues des factures
├── dashboard/                   # Tableaux de bord
└── auth/                        # Authentification
```

### Commandes utiles

```bash
# Reset de la base de données
php artisan migrate:fresh --seed

# Cache clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Tests
php artisan test
```

## Support

Pour toute question ou problème :
1. Vérifier les logs dans `storage/logs/laravel.log`
2. S'assurer que tous les prérequis sont installés
3. Vérifier les permissions sur le dossier `storage/`

## Licence

MIT License - Voir le fichier LICENSE pour plus de détails. 