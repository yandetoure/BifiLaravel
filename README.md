# Bifi - Plateforme de Paiement de Factures CMA

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel Version">
<img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP Version">
<img src="https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
<img src="https://img.shields.io/badge/Status-Production%20Ready-green?style=for-the-badge" alt="Status">
</p>

## 🎯 Description

**Bifi** est une application web moderne et sécurisée développée avec Laravel pour la gestion et le paiement de factures CMA (entreprises de services publics). Elle offre une expérience utilisateur fluide avec des fonctionnalités avancées comme l'extraction automatique de données par OCR.

## ✨ Fonctionnalités

### 🏠 Page d'accueil
- Interface moderne et responsive
- Présentation claire des services
- Invitation intuitive au paiement
- Section entreprises partenaires

### 📄 Gestion des factures
- **Upload automatique** : Extraction OCR des données de facture
- **Saisie manuelle** : Formulaire intuitif pour les informations
- **Support multi-formats** : PDF, JPG, PNG
- **Validation en temps réel** des données

### 👥 Système de rôles
- **Clients** : Soumission et suivi des factures
- **Agents** : Validation et traitement des paiements
- **Superviseurs** : Vue d'ensemble et administration

### 📊 Tableaux de bord
- **Dashboard client** : Historique personnel et statuts
- **Dashboard agent** : Gestion complète des factures
- **Statistiques en temps réel** par statut
- **Filtres et recherche avancée**

### 🔄 Workflow de traitement
1. **Soumission** → Statut "En attente"
2. **Validation** → Statut "Confirmée" 
3. **Paiement** → Statut "Payée"
4. **Reçu** → Génération automatique

## 🚀 Installation rapide

```bash
# 1. Installer les dépendances
composer install && npm install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Base de données
php artisan migrate:fresh --seed

# 4. Stockage
php artisan storage:link

# 5. Démarrage
php artisan serve
```

➡️ **Guide complet** : Voir [INSTALLATION.md](INSTALLATION.md)

## 👤 Comptes de démonstration

| Rôle | Email | Mot de passe | Accès |
|------|--------|--------------|--------|
| **Superviseur** | `admin@bifi.com` | `password` | Administration complète |
| **Agent** | `agent1@bifi.com` | `password` | Validation des factures |
| **Client** | `client1@example.com` | `password` | Soumission de factures |

## 🏢 Entreprises partenaires

- **CMA EDL** - Électricité du Laos
- **CMA Télécom** - Télécommunications
- **CMA Distribution d'Eau** - Services d'eau
- **CMA Services Urbains** - Services municipaux
- **CMA Gaz & Énergie** - Distribution de gaz
- **CMA Transport Public** - Transport en commun

## 🛠 Technologies utilisées

- **Backend** : Laravel 11.x, PHP 8.2+
- **Frontend** : Bootstrap 5.3, Tailwind CSS, jQuery
- **Base de données** : MySQL/PostgreSQL/SQLite
- **OCR** : Tesseract OCR pour l'extraction automatique
- **Upload** : Intervention Image pour le traitement
- **Interface** : Font Awesome, design responsive

## 📱 Interface utilisateur

### Page d'accueil
- Design moderne avec dégradés et animations
- Call-to-action clairs et incitatifs
- Présentation des étapes du processus
- Section entreprises avec logos

### Formulaires
- Validation en temps réel
- Upload par glisser-déposer
- Feedback visuel immédiat
- Messages d'erreur contextuels

### Tableaux de bord
- Cartes statistiques colorées
- Tableaux avec tri et filtres
- Actions en masse possibles
- Modales pour les détails

## 🔐 Sécurité

- Authentification Laravel intégrée
- Validation stricte des données
- Protection CSRF
- Gestion sécurisée des uploads
- Contrôle d'accès par rôles

## 📊 Statuts des factures

| Statut | Description | Actions disponibles |
|--------|-------------|-------------------|
| **En attente** | Facture soumise, en cours de vérification | Confirmer / Annuler |
| **Confirmée** | Validée par un agent, prête pour paiement | Traiter le paiement |
| **Payée** | Paiement effectué avec succès | Générer le reçu |
| **Annulée** | Facture rejetée avec motif | Consulter le motif |

## 🎨 Design moderne

- **Couleurs** : Palette indigo et jaune pour CMA
- **Typographie** : Nunito pour une lecture optimale
- **Icons** : Font Awesome 6 pour la cohérence
- **Responsive** : Compatible mobile et desktop
- **UX** : Transitions fluides et feedback visuel

## 📈 Fonctionnalités avancées

- **OCR intelligent** : Extraction automatique des données
- **Multi-upload** : Support de plusieurs formats
- **Notifications** : Alertes en temps réel
- **Historique** : Traçabilité complète des actions
- **Export** : Génération de reçus PDF
- **Recherche** : Filtres multiples et recherche textuelle

## 🌐 Déploiement

L'application est prête pour la production avec :
- Configuration d'environnement sécurisée
- Optimisation des assets avec Vite
- Cache Redis/Memcached (optionnel)
- Queue pour les tâches lourdes
- Monitoring des erreurs

## 📞 Support

- **Documentation** : [INSTALLATION.md](INSTALLATION.md)
- **Issues** : Utiliser les GitHub Issues
- **Email** : support@bifi.com (exemple)

## 📄 Licence

MIT License - L'application est libre d'utilisation et de modification.

---

<p align="center">
<strong>Développé avec ❤️ pour simplifier le paiement des factures CMA</strong>
</p>

## Corrections Récentes

### 🔧 Accès Paiement Clients
- **Problème résolu** : Les clients peuvent maintenant procéder au paiement de leurs factures confirmées
- **Sécurité** : Vérification que les clients ne peuvent payer que leurs propres factures
- **Statut requis** : Les factures doivent être au statut "confirmed" pour être payables par les clients
- **Interface** : Bouton "Payer" visible dans la liste des factures clients

### 💰 Système Unifié des Balances
- **Cohérence** : Admin, superviseurs et agents voient maintenant les mêmes montants pour :
  - Solde Wizall actuel
  - Solde Wave
  - Caisse espèces  
  - Orange Money
  - Montant à rendre aux agents
- **Synchronisation** : Méthode `getUnifiedBalanceView()` pour assurer la cohérence
- **Versements** : Logique unifiée des versements avec impact temps réel sur tous les rôles
- **Mise à jour automatique** : Synchronisation après chaque opération de versement

### 🔄 Améliorations Techniques
- Vue unifiée des balances : `Balance::getUnifiedBalanceView()`
- Synchronisation automatique : `synchronizeBalances()` 
- Contrôle d'accès renforcé pour les paiements clients
- Cache de routes et configuration nettoyé

### 📊 Fonctionnalités Validées
- ✅ Clients peuvent voir leurs factures (`/my/bills`)
- ✅ Clients peuvent payer leurs factures confirmées
- ✅ Clients peuvent télécharger leurs reçus (`/my/receipts`)
- ✅ Vue unifiée des balances pour tous les rôles
- ✅ Versements synchronisés en temps réel
- ✅ Navigation adaptée selon le rôle utilisateur

## Structure des Rôles

### 👤 Client
- Soumission de nouvelles factures
- Visualisation de ses factures
- **NOUVEAU** : Paiement des factures confirmées
- Téléchargement des reçus
- Support client via chat

### 👨‍💼 Agent
- Traitement des paiements
- Vue unifiée des balances (partagée)
- Versements en espèces vers Wizall
- Dashboard avec statistiques personnelles

### 👑 Superviseur
- Toutes les fonctions agent
- Vue unifiée des balances (partagée) 
- Versements Wizall (augmente dette agent)
- Calculs fin de journée
- Dashboard étendu

### 🔐 Admin
- Toutes les fonctions superviseur
- Vue unifiée des balances (partagée)
- Gestion utilisateurs
- Configuration système
- Rapports complets

## Balances Unifiées

Tous les rôles voient désormais les mêmes données :
```php
$balanceData = Balance::getUnifiedBalanceView();
// Retourne :
// - wizall_current_balance (identique pour tous)
// - cash_balance (identique pour tous)  
// - wave_final_balance (identique pour tous)
// - agent_return_amount (identique pour tous)
// - deposits_summary (identique pour tous)
```

## Sécurité Paiements Clients

```php
// Vérifications automatiques :
- Facture au statut "confirmed" uniquement
- Client propriétaire de la facture uniquement
- Correspondance nom/téléphone/email
- Facture non déjà payée
```
