# 🚀 GUIDE D'INSTALLATION - Hospital Pro

## ⚡ Installation Rapide

### 1. Configuration de la Base de Données

Le projet est configuré par défaut pour utiliser **MySQL**. Vous avez 2 options :

#### Option A : Utiliser MySQL (Recommandé)

```bash
# Créer la base de données
mysql -u root -p -e "CREATE DATABASE hospital_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Copier le fichier .env
cp .env.mysql .env

# Générer la clé d'application
php artisan key:generate

# Lancer les migrations
php artisan migrate:fresh --seed
```

#### Option B : Utiliser SQLite (Développement uniquement)

```bash
# Installer l'extension PHP SQLite si nécessaire
# Ubuntu/Debian : sudo apt-get install php-sqlite3
# macOS : php-sqlite3 est inclus par défaut

# Créer le fichier de base de données
touch database/database.sqlite

# Configurer le .env pour SQLite
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
# Commenter les lignes DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Générer la clé
php artisan key:generate

# Lancer les migrations
php artisan migrate:fresh --seed
```

### 2. Compiler les Assets

```bash
# Installation des dépendances
npm install

# Build pour production
npm run build

# OU pour développement avec hot-reload
npm run dev
```

### 3. Lancer le Serveur

```bash
php artisan serve
```

Accéder à : **http://localhost:8000**

---

## 🔑 Comptes de Test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| **Admin Principal** | admin@hospital.com | admin123 |
| Médecin | medecin@hospital.com | password |
| Patient | patient@hospital.com | password |
| Secrétaire | secretaire@hospital.com | password |
| Caissier | caissier@hospital.com | password |

---

## 📦 Dépendances Installées

### Backend (Composer)
- `laravel/framework: ^12.0` - Framework Laravel
- `laravel/breeze: ^2.3` - Authentification
- `barryvdh/laravel-dompdf: ^3.1` - Génération PDF

### Frontend (NPM)
- `alpinejs` - Framework JavaScript réactif
- `@alpinejs/focus` - Plugin Alpine.js
- `apexcharts` - Bibliothèque de graphiques
- `tailwindcss: ^4.0` - Framework CSS
- `vite` - Build tool moderne

---

## 🗂️ Structure de la Base de Données

### Tables Principales

1. **roles** - Rôles utilisateurs (Admin, Médecin, Patient, etc.)
2. **users** - Utilisateurs avec leur rôle
3. **services** - Services médicaux (Dentaire, Consultation, Analyses)
4. **type_services** - Types de services avec prix et durée
5. **rendez_vous** - Rendez-vous patients
6. **paiements** - Paiements des consultations
7. **factures** - Factures générées
8. **menus** - Navigation dynamique par rôle
9. **audit_logs** - Logs des actions utilisateurs

### Données de Test Créées

#### Rôles (5)
- Admin
- Médecin
- Patient
- Secrétaire
- Caissier

#### Services (3)
1. **Dentaire** 
   - Extraction dentaire (50 000 FCFA)
   - Pose de bagues (250 000 FCFA)
   - Nettoyage dentaire (30 000 FCFA)
   - Plombage (40 000 FCFA)

2. **Consultation**
   - Consultation générale (20 000 FCFA)
   - Consultation spécialisée (40 000 FCFA)
   - Suivi médical (15 000 FCFA)

3. **Analyses**
   - Prise de sang (25 000 FCFA)
   - Radiographie (35 000 FCFA)
   - Échographie (45 000 FCFA)

#### Menus Dynamiques
- Navigation adaptée selon le rôle de l'utilisateur
- Icônes personnalisées pour chaque menu

---

## 🎨 Fonctionnalités du Design

### Interface Futuriste
- ✨ Dégradés cyberpunk (noir → violet → bleu)
- 💎 Effets glassmorphism (verre dépoli)
- 🌟 Néons lumineux avec glow
- ⚡ Animations fluides
- 🎭 Micro-interactions sur hover
- 📱 Responsive design complet

### Composants Blade Réutilisables
```blade
<x-card>
    <x-slot name="header">Titre</x-slot>
    Contenu...
</x-card>

<x-button variant="primary">Bouton</x-button>
<x-button variant="secondary">Bouton</x-button>
<x-button variant="danger">Bouton</x-button>

<x-input label="Nom" name="nom" required />

<x-modal show="false" maxWidth="2xl">
    Contenu modal...
</x-modal>
```

### Variantes de Boutons
- `primary` - Cyan → Blue
- `secondary` - Purple → Pink
- `success` - Green → Emerald
- `danger` - Red → Pink
- `outline` - Transparent avec bordure

---

## 🔧 Commandes Utiles

```bash
# Réinitialiser complètement la base de données
php artisan migrate:fresh --seed

# Effacer tous les caches
php artisan optimize:clear

# Créer un nouveau contrôleur
php artisan make:controller NomController

# Créer un nouveau modèle avec migration
php artisan make:model NomModele -m

# Créer un seeder
php artisan make:seeder NomSeeder

# Lancer les tests
php artisan test

# Générer une clé d'application
php artisan key:generate

# Lister toutes les routes
php artisan route:list

# Vérifier la configuration
php artisan about
```

---

## 🛠️ Résolution des Problèmes

### Erreur: "could not find driver"
```bash
# Installer l'extension PHP appropriée
# MySQL
sudo apt-get install php-mysql

# PostgreSQL
sudo apt-get install php-pgsql

# SQLite
sudo apt-get install php-sqlite3

# Redémarrer le serveur web
sudo service apache2 restart
# ou
sudo service nginx restart
```

### Erreur: "SQLSTATE[HY000] [1049] Unknown database"
```bash
# Créer la base de données
mysql -u root -p -e "CREATE DATABASE hospital_pro;"
```

### Erreur: "Class 'DOMDocument' not found"
```bash
# Installer l'extension PHP XML
sudo apt-get install php-xml
```

### Erreur npm: "Unsupported engine"
```bash
# Mettre à jour Node.js vers la version 20.19+ ou 22.12+
# Utiliser nvm (Node Version Manager)
nvm install 22
nvm use 22
```

### Erreur de permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📱 Accès aux Différents Dashboards

Après connexion, vous serez automatiquement redirigé vers votre dashboard selon votre rôle :

- **Admin** → `/admin/dashboard`
- **Médecin** → `/medecin/dashboard`
- **Patient** → `/patient/dashboard`
- **Secrétaire** → `/admin/dashboard`
- **Caissier** → `/admin/paiements`

---

## 🚀 Prochaines Étapes

1. **Tester l'application** avec les comptes fournis
2. **Créer des rendez-vous** en tant que patient
3. **Confirmer des rendez-vous** en tant que médecin
4. **Gérer les paiements** en tant qu'admin
5. **Explorer le dashboard** et les graphiques

---

## 📞 Support

Pour toute question ou problème :
1. Vérifier ce guide d'installation
2. Consulter les logs : `storage/logs/laravel.log`
3. Utiliser `php artisan about` pour diagnostiquer

---

**Bon développement ! 🎉**
