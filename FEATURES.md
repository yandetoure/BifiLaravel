# Fonctionnalités BIFI - Système de Gestion des Paiements

## Vue d'ensemble

Ce système permet aux agents de traiter les paiements de factures avec extraction automatique des données via OCR, génération de reçus PDF, et gestion complète des soldes quotidiens.

## Fonctionnalités Principales

### 1. Gestion des Paiements de Factures

#### Processus de Paiement
1. **Vérification du statut** : Seules les factures confirmées peuvent être payées
2. **Upload du reçu** : L'agent télécharge une photo/capture du reçu de paiement
3. **Extraction OCR** : Extraction automatique des données du reçu via Tesseract OCR
4. **Validation et ajustement** : L'agent peut modifier les données extraites si nécessaire
5. **Génération automatique du reçu** : PDF généré automatiquement avec logo et signature
6. **Envoi optionnel** : Envoi du reçu par email et/ou WhatsApp

#### Données Extraites Automatiquement
- Référence de transaction
- Date de transaction
- Type de transaction
- Montant
- Frais (calculés automatiquement à 1%)
- Total

### 2. Gestion des Soldes Quotidiens

#### Types de Soldes Gérés
- **Wizall** : Solde de départ, courant et final
- **Wave** : Solde de départ et final
- **Orange Money** : Solde disponible
- **Cash** : Solde en espèces

#### Fonctionnalités
- **Initialisation quotidienne** : Reprise des soldes finaux de la veille
- **Mise à jour en temps réel** : Calcul automatique après chaque paiement
- **Versements à la banque** : Traçabilité des dépôts effectués
- **Versements superviseur** : Gestion des fonds additionnels à rendre

### 3. Système de Versements

#### Versements Agents
- Versements depuis les comptes disponibles (Cash, Wizall, Wave, OM)
- Impact automatique sur les soldes
- Traçabilité complète avec agent responsable

#### Versements Superviseur
- Ajout de fonds opérationnels
- Montants à rendre en fin de journée
- Séparation claire des fonds BIFI vs superviseur

### 4. Génération de Reçus PDF

#### Contenu du Reçu
- Logo et informations de l'entreprise BICONSULTING
- Numéro de reçu unique (format: NRyymmddhhmmss)
- Détails du client et de la transaction
- Récapitulatif des montants
- Informations de l'agent responsable
- Signature et cachet

#### Options d'Envoi
- **Email** : Envoi automatique si adresse fournie
- **WhatsApp** : Envoi via API (à configurer)
- **Téléchargement** : PDF disponible immédiatement

### 5. Extraction OCR Avancée

#### Reconnaissance de Texte
- Support du français (Tesseract OCR)
- Extraction de données structurées
- Gestion des erreurs et corrections manuelles
- Formats supportés : JPEG, PNG, JPG

#### Données Reconnues
- Références de transaction
- Dates et heures
- Montants et devises
- Types de transaction
- Numéros clients

## Architecture Technique

### Modèles de Données

#### Payment
```php
- bill_id (relation vers Bill)
- agent_id (relation vers User)
- client_name (nom du client)
- transaction_reference
- transaction_type
- amount, fees, total
- payment_method (wizall, wave, orange_money, cash)
- transaction_date
- proof_image (fichier preuve)
```

#### Balance
```php
- date (date du solde)
- wizall_start_balance, wizall_current_balance, wizall_final_balance
- wave_start_balance, wave_final_balance
- orange_money_balance, cash_balance
- total_to_return (montant à rendre au superviseur)
```

#### Transaction
```php
- user_id (agent ou superviseur)
- type (deposit, supervisor_deposit)
- amount, from_account, to_account
- description, status
```

#### Receipt
```php
- payment_id (relation vers Payment)
- receipt_number (numéro unique)
- client_name, file_path
- sent_by_email, sent_by_whatsapp (statuts d'envoi)
```

### Contrôleurs

#### PaymentController
- Création et traitement des paiements
- Extraction OCR des reçus
- Génération automatique des reçus PDF
- Mise à jour des soldes

#### BalanceController
- Gestion des soldes quotidiens
- Initialisation des journées
- Versements et dépôts
- Calculs automatiques

#### ReceiptController
- Génération des PDF
- Téléchargement des reçus
- Envoi par email/WhatsApp

### Vues Principales

#### payments/create.blade.php
- Interface moderne d'upload et traitement
- Extraction OCR en temps réel
- Formulaire dynamique avec calculs automatiques
- Options d'envoi configurables

#### balances/index.blade.php
- Dashboard des soldes en temps réel
- Actions rapides (initialisation, versements)
- Modals pour les opérations
- Interface superviseur dédiée

#### payments/success.blade.php
- Récapitulatif complet du paiement
- Accès direct au reçu PDF
- Statuts d'envoi
- Actions de suivi

## Configuration

### Variables d'Environnement
```env
COMPANY_NAME="BICONSULTING"
COMPANY_EMAIL="diarrabicons@gmail.com"
COMPANY_PHONE="+221 78 705 67 67"
PAYMENT_FEE_RATE=0.01
RECEIPT_NUMBER_PREFIX="NR"
TESSERACT_LANGUAGE="fra"
NOTIFICATIONS_EMAIL_ENABLED=true
NOTIFICATIONS_WHATSAPP_ENABLED=false
```

### Fichier config/bifi.php
Centralisation de toutes les configurations spécifiques à l'application.

## Sécurité et Traçabilité

### Audit Trail
- Chaque paiement est lié à un agent spécifique
- Historique complet des versements
- Horodatage de toutes les opérations
- Conservation des preuves de paiement

### Validation des Données
- Validation stricte des formulaires
- Vérification des statuts de factures
- Contrôle des permissions (superviseur vs agent)
- Gestion des erreurs gracieuse

## Workflow Quotidien

### Début de Journée
1. Initialisation des soldes (reprise de la veille)
2. Mise à jour des soldes Wave, OM et Cash
3. Vérification des montants disponibles

### Traitement des Paiements
1. Réception de la facture confirmée
2. Upload du reçu de paiement
3. Extraction automatique des données
4. Validation et ajustement si nécessaire
5. Génération et envoi du reçu

### Gestion des Fonds
1. Versements à la banque selon les besoins
2. Versements superviseur si nécessaire
3. Suivi en temps réel des soldes

### Fin de Journée
1. Vérification des soldes finaux
2. Calcul du montant à rendre au superviseur
3. Rapports et réconciliation

## Améliorations Futures

### Intégrations Possibles
- API WhatsApp Business pour l'envoi automatique
- Système de notifications SMS
- Intégration bancaire pour les versements
- Dashboard analytique avancé

### Fonctionnalités Additionnelles
- Rapports journaliers/mensuels automatiques
- Système d'alertes pour soldes bas
- Gestion multi-devises
- Sauvegarde automatique des données

## Installation et Déploiement

### Prérequis
- PHP 8.1+
- Laravel 10+
- Tesseract OCR installé
- Extension PHP GD ou Imagick
- Base de données MySQL/PostgreSQL

### Installation
```bash
# Cloner le projet
git clone [repository]

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate

# Assets
npm run build

# Permissions de stockage
php artisan storage:link
chmod -R 775 storage
```

Cette implémentation fournit un système complet et robuste pour la gestion des paiements de factures avec toutes les fonctionnalités demandées. 