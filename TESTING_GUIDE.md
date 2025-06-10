# Guide de Test - Système BIFI

## Prérequis pour les Tests

### 1. Configuration Base
- Serveur Laravel démarré : `php artisan serve --port=8002`
- Base de données migrée : `php artisan migrate`
- Tesseract OCR installé (pour l'extraction de texte)
- Stockage configuré : `php artisan storage:link`

### 2. Données de Test
Créer quelques utilisateurs avec différents rôles :
- Agent (role: 'agent')
- Superviseur (role: 'supervisor')
- Client (role: 'client')

### 3. Factures de Test
Créer quelques factures avec statut 'confirmed' pour tester les paiements.

## Scénarios de Test

### Test 1: Gestion des Soldes

#### URL : http://localhost:8002/balances

#### Tests à effectuer :
1. **Initialisation des soldes**
   - Cliquer sur "Initialiser la journée"
   - Vérifier que les soldes sont créés avec les valeurs par défaut

2. **Mise à jour des soldes**
   - Cliquer sur "Modifier les soldes"
   - Saisir des valeurs pour Wave, Orange Money, Cash
   - Vérifier que les valeurs sont mises à jour

3. **Versements agent**
   - Cliquer sur "Nouveau versement"
   - Tester les versements depuis différents comptes
   - Vérifier l'impact sur les soldes

4. **Versements superviseur** (connecté en tant que superviseur)
   - Tester le versement superviseur
   - Vérifier que le montant s'ajoute au "Total à rendre"

### Test 2: Paiement de Factures avec OCR

#### URL : http://localhost:8002/payments/create/{bill_id}

#### Préparer des images de test :
Créer des images avec du texte simulant un reçu :
```
REFERENCE DE LA TRANSACTION
TX123456789

DATE DE TRANSACTION
05/06/2025 14:30

TYPE DE TRANSACTION
Paiement facture

MONTANT
50000 FCFA
```

#### Tests à effectuer :
1. **Upload et extraction**
   - Télécharger une image de reçu
   - Cliquer sur "Analyser le reçu"
   - Vérifier que les données sont extraites correctement

2. **Validation et correction**
   - Modifier les données extraites si nécessaire
   - Saisir le nom du client
   - Vérifier le calcul automatique des frais (1%)

3. **Options d'envoi**
   - Cocher "Envoyer par email" et saisir un email
   - Cocher "Envoyer par WhatsApp" et saisir un numéro
   - Tester sans options d'envoi

4. **Soumission**
   - Soumettre le formulaire
   - Vérifier la redirection vers la page de succès

### Test 3: Génération et Gestion des Reçus

#### Après un paiement réussi :

1. **Vérification du reçu**
   - Vérifier que le reçu est généré automatiquement
   - Cliquer sur "Télécharger PDF"
   - Vérifier le contenu du PDF (logo, informations, signature)

2. **Statuts d'envoi**
   - Vérifier les statuts d'envoi (email/WhatsApp)
   - Tester les fonctions d'envoi manuel si implémentées

### Test 4: Intégration des Soldes et Paiements

#### Test du flux complet :

1. **État initial**
   - Initialiser les soldes du jour
   - Noter les montants de départ

2. **Effectuer un paiement Wizall**
   - Traiter un paiement via Wizall
   - Vérifier que le solde Wizall diminue du montant total

3. **Effectuer un versement**
   - Faire un versement depuis Cash vers la banque
   - Vérifier l'impact sur le solde Cash

4. **Vérifier les calculs**
   - Aller sur la page des soldes
   - Vérifier que le "Total à rendre" est correct

### Test 5: Permissions et Sécurité

#### Tests de permissions :

1. **Agent normal**
   - Ne doit pas voir l'option "Versement Superviseur"
   - Peut effectuer tous les autres versements

2. **Superviseur**
   - Voit toutes les options
   - Peut effectuer des versements superviseur

3. **Validation des données**
   - Tenter de soumettre des formulaires incomplets
   - Vérifier les messages d'erreur

### Test 6: Responsivité et UX

#### Tests d'interface :

1. **Navigation mobile**
   - Tester sur différentes tailles d'écran
   - Vérifier que les modals fonctionnent

2. **Feedback utilisateur**
   - Vérifier les messages de succès/erreur
   - Tester les states de loading (boutons désactivés, spinners)

3. **Calculs en temps réel**
   - Modifier le montant dans le formulaire de paiement
   - Vérifier que les frais et total se mettent à jour

## Points de Vérification Critiques

### 1. Traçabilité
- Chaque transaction doit être liée à un utilisateur
- Les horodatages doivent être corrects
- Les preuves de paiement doivent être conservées

### 2. Intégrité des Données
- Les calculs de soldes doivent être exacts
- Les montants ne doivent jamais être négatifs par erreur
- Les relations entre modèles doivent être cohérentes

### 3. Performance
- L'extraction OCR ne doit pas bloquer l'interface
- La génération PDF doit être rapide
- Les requêtes de base de données doivent être optimisées

### 4. Gestion d'Erreurs
- Upload de fichiers invalides
- Données OCR non reconnues
- Erreurs de réseau pour l'envoi

## Données de Test Recommandées

### Utilisateurs
```
Agent 1: agent@bifi.com / password
Superviseur: supervisor@bifi.com / password
```

### Factures
```
Facture 1: Numéro FAC001, Client 123456, Montant 50000, Statut confirmed
Facture 2: Numéro FAC002, Client 789012, Montant 75000, Statut confirmed
```

### Images de Test OCR
Créer des images avec texte clair, bon contraste, différents formats de reçus pour tester la robustesse de l'extraction.

## Résultats Attendus

### Après Tests Complets
- Tous les paiements traités avec succès
- Reçus PDF générés et téléchargeables
- Soldes mis à jour correctement
- Versements tracés et comptabilisés
- Interface responsive et intuitive

### Métriques de Performance
- Extraction OCR : < 5 secondes
- Génération PDF : < 2 secondes
- Chargement des pages : < 1 seconde

Ce guide garantit une validation complète de toutes les fonctionnalités implémentées. 



admin est un role ne l'oubli pas, les autres ne peuvent pas supprimer mais archiver, l'admin lui peut tout voir et tout faire, continue pour les pages manquantes, inspire toi de cette page pour faire la page d'accueil, utilise du tallwind pour toutes les pages et garantie de belles interfaces fluide:@https://www.biconsulting.biz/ , pour les calcul on doit ajouter les transactions dans un tableau excel, pour les paiement l'agent a la possibilité de dire le montant qu'il a recu et le montant de la monnaie qui est calculé directement, il doit mettre le moyen de rendre la monaie comme wave ou om ou cash pour qu'on puisse faire le calcule. Je te partage un tableau excel que j'avais fait pour t'aider:@https://docs.google.com/spreadsheets/d/1RAgDNtv1hE3tF2-jbuJYI9c754jc982qtIFmF8N0GvM/edit?gid=0#gid=0 