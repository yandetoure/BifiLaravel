#!/bin/bash

# Bifi - Script d'installation automatique
# Usage: ./install.sh

echo "🚀 Installation de Bifi - Plateforme de Paiement CMA"
echo "=================================================="

# Vérification des prérequis
echo "📋 Vérification des prérequis..."

# Vérifier PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé. Veuillez installer PHP 8.2+"
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP $PHP_VERSION détecté"

# Vérifier Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé. Veuillez installer Composer"
    exit 1
fi
echo "✅ Composer détecté"

# Vérifier Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js n'est pas installé. Veuillez installer Node.js"
    exit 1
fi

NODE_VERSION=$(node --version)
echo "✅ Node.js $NODE_VERSION détecté"

echo ""
echo "📦 Installation des dépendances..."

# Installation des dépendances PHP
echo "🐘 Installation des packages PHP avec Composer..."
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'installation des dépendances PHP"
    exit 1
fi

# Installation des dépendances JavaScript
echo "📦 Installation des packages JavaScript avec npm..."
npm install

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de l'installation des dépendances JavaScript"
    exit 1
fi

echo ""
echo "⚙️ Configuration de l'application..."

# Copier le fichier .env s'il n'existe pas
if [ ! -f ".env" ]; then
    echo "📝 Création du fichier .env..."
    cp .env.example .env
    
    if [ $? -ne 0 ]; then
        echo "❌ Impossible de créer le fichier .env"
        exit 1
    fi
else
    echo "📝 Fichier .env déjà existant"
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate --force

# Configuration de la base de données
echo ""
echo "🗄️ Configuration de la base de données..."
echo "Veuillez configurer votre base de données dans le fichier .env"
echo "Puis appuyez sur Entrée pour continuer..."
read -p "Fichier .env configuré ? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "⚠️ Veuillez configurer votre base de données dans .env et relancer le script"
    exit 1
fi

# Exécution des migrations et seeders
echo "🗃️ Création des tables et insertion des données de test..."
php artisan migrate:fresh --seed --force

if [ $? -ne 0 ]; then
    echo "❌ Erreur lors de la migration de la base de données"
    echo "ℹ️ Vérifiez votre configuration de base de données dans .env"
    exit 1
fi

# Création du lien symbolique pour le stockage
echo "🔗 Configuration du stockage public..."
php artisan storage:link

# Création des dossiers nécessaires
echo "📁 Création des dossiers de stockage..."
mkdir -p storage/app/public/bills
mkdir -p storage/app/public/companies
chmod -R 755 storage

# Compilation des assets
echo "🎨 Compilation des assets frontend..."
npm run build

if [ $? -ne 0 ]; then
    echo "⚠️ Erreur lors de la compilation des assets"
    echo "ℹ️ Vous pouvez utiliser 'npm run dev' pour le développement"
fi

echo ""
echo "🎉 Installation terminée avec succès !"
echo "======================================="
echo ""
echo "📋 Prochaines étapes :"
echo "1. Démarrer le serveur : php artisan serve"
echo "2. Ouvrir http://localhost:8000 dans votre navigateur"
echo ""
echo "👤 Comptes de test :"
echo "• Administrateur : admin@bifi.com / password"
echo "• Agent : agent1@bifi.com / password"  
echo "• Client : client1@example.com / password"
echo ""
echo "📚 Documentation complète : voir INSTALLATION.md"
echo ""
echo "🚀 Démarrage automatique du serveur dans 3 secondes..."
sleep 3

# Démarrage du serveur de développement
echo "🌐 Démarrage du serveur de développement..."
php artisan serve 