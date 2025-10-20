#!/bin/bash

echo "🏥 Installation de Hospital Pro 🚀"
echo "=================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Vérifier PHP
print_info "Vérification de PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    print_success "PHP $PHP_VERSION installé"
else
    print_error "PHP n'est pas installé"
    exit 1
fi

# Vérifier Composer
print_info "Vérification de Composer..."
if command -v composer &> /dev/null; then
    print_success "Composer installé"
else
    print_error "Composer n'est pas installé"
    exit 1
fi

# Vérifier Node.js
print_info "Vérification de Node.js..."
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    print_success "Node.js $NODE_VERSION installé"
else
    print_error "Node.js n'est pas installé"
    exit 1
fi

# Installer les dépendances PHP
print_info "Installation des dépendances PHP..."
composer install --no-interaction
print_success "Dépendances PHP installées"

# Installer les dépendances NPM
print_info "Installation des dépendances NPM..."
npm install
print_success "Dépendances NPM installées"

# Choisir la base de données
echo ""
echo "Quelle base de données souhaitez-vous utiliser ?"
echo "1) MySQL (Recommandé)"
echo "2) PostgreSQL"
echo "3) SQLite (Développement uniquement)"
read -p "Votre choix (1-3): " db_choice

case $db_choice in
    1)
        print_info "Configuration pour MySQL..."
        if [ -f ".env.mysql" ]; then
            cp .env.mysql .env
            print_success "Fichier .env créé avec configuration MySQL"
        else
            cp .env.example .env
            sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
            print_success "Fichier .env créé"
        fi
        
        read -p "Nom de la base de données [hospital_pro]: " db_name
        db_name=${db_name:-hospital_pro}
        
        read -p "Utilisateur MySQL [root]: " db_user
        db_user=${db_user:-root}
        
        read -sp "Mot de passe MySQL: " db_pass
        echo ""
        
        # Mettre à jour le .env
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$db_name/" .env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_user/" .env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_pass/" .env
        
        # Créer la base de données
        print_info "Création de la base de données..."
        mysql -u$db_user -p$db_pass -e "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
        if [ $? -eq 0 ]; then
            print_success "Base de données créée"
        else
            print_warning "Impossible de créer la base de données automatiquement"
            print_info "Créez-la manuellement avec: mysql -u$db_user -p -e \"CREATE DATABASE $db_name;\""
        fi
        ;;
    2)
        print_info "Configuration pour PostgreSQL..."
        cp .env.example .env
        sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
        print_success "Fichier .env créé"
        ;;
    3)
        print_info "Configuration pour SQLite..."
        cp .env.example .env
        touch database/database.sqlite
        print_success "Fichier .env et database.sqlite créés"
        ;;
    *)
        print_error "Choix invalide"
        exit 1
        ;;
esac

# Générer la clé d'application
print_info "Génération de la clé d'application..."
php artisan key:generate --force
print_success "Clé d'application générée"

# Exécuter les migrations et seeders
print_info "Création des tables et données de test..."
php artisan migrate:fresh --seed --force
if [ $? -eq 0 ]; then
    print_success "Base de données initialisée"
else
    print_error "Erreur lors de la création de la base de données"
    print_info "Vérifiez vos paramètres de connexion dans le fichier .env"
    exit 1
fi

# Compiler les assets
print_info "Compilation des assets..."
npm run build
print_success "Assets compilés"

# Créer les liens symboliques pour le stockage
print_info "Création des liens symboliques..."
php artisan storage:link
print_success "Liens symboliques créés"

# Afficher le résumé
echo ""
echo "=================================="
print_success "Installation terminée ! 🎉"
echo "=================================="
echo ""
echo "📋 Comptes de test créés :"
echo "   Admin:      admin@hospital.com / admin123"
echo "   Médecin:    medecin@hospital.com / password"
echo "   Patient:    patient@hospital.com / password"
echo "   Secrétaire: secretaire@hospital.com / password"
echo "   Caissier:   caissier@hospital.com / password"
echo ""
echo "🚀 Pour démarrer le serveur :"
echo "   php artisan serve"
echo ""
echo "🌐 Puis ouvrez votre navigateur sur :"
echo "   http://localhost:8000"
echo ""
echo "💻 Pour le développement avec hot-reload :"
echo "   npm run dev"
echo ""
echo "=================================="
