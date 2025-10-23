# 💳 Système de Portefeuille Virtuel (Wallet)

## 📋 Vue d'ensemble

Le système de portefeuille virtuel permet aux patients de :
- Recharger leur compte en FBU (Franc Burundais)
- Payer leurs rendez-vous directement lors de la réservation
- Consulter l'historique complet de leurs transactions
- Gérer leur solde de manière autonome

---

## 🗄️ Structure de la Base de Données

### Table `wallets`
```sql
- id (primary key)
- user_id (foreign key -> users, unique)
- solde (decimal 15,2, default 0) - Solde en FBU
- is_active (boolean, default true)
- devise (string, default 'FBU')
- timestamps
```

### Table `transactions`
```sql
- id (primary key)
- wallet_id (foreign key -> wallets)
- type (enum: rechargement, paiement, remboursement, retrait)
- montant (decimal 15,2)
- solde_avant (decimal 15,2)
- solde_apres (decimal 15,2)
- reference (string, unique) - Ex: RCH-xxxxx, PAY-xxxxx
- description (text)
- statut (enum: en_attente, reussi, echoue, annule)
- rendez_vous_id (foreign key -> rendez_vous, nullable)
- methode_rechargement (string, nullable)
- metadata (json) - Informations supplémentaires
- timestamps
```

---

## 🎯 Fonctionnalités Implémentées

### 1. **Gestion du Wallet**

#### Modèle `Wallet` (`app/Models/Wallet.php`)
Méthodes principales :
- `credit($montant, $type, $metadata)` - Créditer le wallet
- `debit($montant, $type, $metadata)` - Débiter le wallet (avec vérification de solde)
- `generateReference($type)` - Générer une référence unique
- `getSoldeFormateAttribute()` - Formater le solde (ex: "50,000 FBU")

Relations :
- `user()` - BelongsTo User
- `transactions()` - HasMany Transaction

#### Modèle `Transaction` (`app/Models/Transaction.php`)
Accesseurs :
- `montant_formate` - Montant formaté avec séparateurs
- `type_color` - Couleur selon le type (vert/rouge/bleu/orange)
- `type_label` - Label en français du type

Relations :
- `wallet()` - BelongsTo Wallet
- `rendezVous()` - BelongsTo RendezVous

#### Modèle `User` (étendu)
Nouvelles méthodes :
- `wallet()` - HasOne Wallet
- `getOrCreateWallet()` - Créer wallet si inexistant

### 2. **Routes Wallet** (`routes/web.php`)
```php
Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/recharger', [WalletController::class, 'recharger'])->name('wallet.recharger');
    Route::post('/wallet/recharger', [WalletController::class, 'storeRechargement'])->name('wallet.store-rechargement');
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
});
```

### 3. **Contrôleur Wallet** (`app/Http/Controllers/Patient/WalletController.php`)

#### `index()`
- Affiche le dashboard du wallet
- Statistiques : total rechargé, total dépensé, nombre de transactions
- Liste des 15 dernières transactions
- Affichage du solde actuel

#### `recharger()`
- Formulaire de rechargement
- 3 méthodes de paiement :
  - 💳 **Mobile Money** (Ecocash, Lumicash) - Nécessite numéro de téléphone
  - 💳 **Carte Bancaire** (Visa, Mastercard) - Nécessite numéro de carte
  - 💵 **Espèces** - À la caisse de l'hôpital
- Montants rapides : 10K, 25K, 50K, 100K, 200K FBU

#### `storeRechargement(Request $request)`
Validation :
- `montant` : required, numeric, min:1000, max:5000000
- `methode` : required, in:mobile_money,carte_bancaire,especes
- `telephone` : required_if:methode,mobile_money
- `numero_carte` : required_if:methode,carte_bancaire

Traitement :
1. Utilise DB::transaction pour atomicité
2. Appelle `wallet->credit()` pour créditer
3. Génère une référence unique
4. Sauvegarde les métadonnées (méthode, opérateur, etc.)
5. Redirige avec message de succès

#### `transactions()`
- Liste complète de l'historique
- Pagination (20 par page)
- Filtre par type de transaction

### 4. **Vues Wallet**

#### `resources/views/patient/wallet/index.blade.php`
- **Card de solde** avec fond animé cyan/bleu
- **Statistiques** en 3 colonnes :
  - Total rechargé (vert)
  - Total dépensé (rouge)
  - Nombre de transactions (cyan)
- **Liste des transactions récentes** (15 dernières)
  - Icônes selon le type
  - Couleurs selon le type
  - Montant formaté
  - Date et heure
- **État vide** avec call-to-action si aucune transaction
- **Card d'information** expliquant le fonctionnement

#### `resources/views/patient/wallet/recharger.blade.php`
- **Formulaire réactif** avec Alpine.js
- **Input montant** avec validation min/max
- **5 boutons rapides** (10K à 200K)
- **3 cartes de sélection** pour méthode de paiement
  - Mobile Money : Input téléphone + select opérateur
  - Carte Bancaire : Input numéro de carte
  - Espèces : Message d'information caisse
- **Card de sécurité** avec conseils

### 5. **Intégration au Rendez-vous**

#### Contrôleur `Patient\RendezVousController`
La méthode `store()` a été étendue :

1. **Validation étendue** :
   ```php
   'payer_maintenant' => 'nullable|boolean'
   ```

2. **Logique de paiement** :
   - Si `payer_maintenant` coché :
     - Récupère le wallet du patient
     - Vérifie le solde suffisant
     - Débite le montant via `wallet->debit()`
     - Crée le `Paiement` avec mode='wallet'
     - Génère la `Facture` automatiquement
     - Utilise DB::transaction pour atomicité
   - Sinon : rendez-vous sans paiement (comme avant)

3. **Gestion des erreurs** :
   - Solde insuffisant → Message avec lien vers rechargement
   - Exception → Rollback + message d'erreur

#### Vue `resources/views/patient/rendez-vous/create.blade.php`
Ajout d'une section "Paiement via Portefeuille" :
- **Card jaune/orange** avec icône wallet
- **Affichage du solde actuel** du patient
- **Checkbox** "Payer maintenant avec mon portefeuille"
- **Section dépliable** montrant (si coché) :
  - Montant à payer (prix du type de service)
  - Solde actuel
  - Solde après paiement (calculé dynamiquement)
  - Alerte verte si solde suffisant
  - Alerte rouge si solde insuffisant (avec lien rechargement)

JavaScript amélioré :
- Récupération du prix lors de la sélection du type de service
- Mise à jour dynamique via Alpine.js
- Calcul automatique du solde après paiement

### 6. **Menu de Navigation**

#### `database/seeders/MenuSeeder.php`
Ajout de l'entrée :
```php
[
    'titre' => 'Mon Portefeuille',
    'lien' => '/patient/wallet',
    'icone' => 'wallet',
    'ordre' => 3,
    'visible_pour' => [$roles['Patient']],
]
```

#### Icône `resources/views/components/icons/wallet.blade.php`
SVG d'icône carte de crédit (déjà existant)

### 7. **Dashboard Patient**

#### `resources/views/patient/dashboard.blade.php`
Ajout de la **4ème card** quick action :
- **Icône wallet** jaune/orange avec effet glow
- **Titre** : "Mon Portefeuille"
- **Description** : "Gérer mes FBU"
- **Lien** : `route('patient.wallet.index')`
- Grid modifié : `grid-cols-1 md:grid-cols-4`

---

## 🔄 Flux de Paiement

### Scénario 1 : Rechargement du Wallet
1. Patient clique sur "Recharger" dans le wallet
2. Saisit le montant (ou clique bouton rapide)
3. Choisit la méthode de paiement
4. Remplit les champs spécifiques (téléphone/carte)
5. Valide le formulaire
6. **Transaction créée** :
   - Type : `rechargement`
   - Statut : `reussi`
   - Référence : `RCH-xxxxx-timestamp`
   - Metadata : méthode, opérateur, etc.
7. Wallet crédité avec nouveau solde
8. Redirection avec message de succès

### Scénario 2 : Paiement lors du Rendez-vous
1. Patient remplit le formulaire de rendez-vous
2. Sélectionne service + type de service
3. **Prix affiché automatiquement** dans la section paiement
4. Coche "Payer maintenant"
5. **Vérification en temps réel** :
   - Si solde ≥ prix → Badge vert "Solde suffisant"
   - Si solde < prix → Badge rouge + lien rechargement
6. Soumet le formulaire
7. **Backend** :
   - Crée le rendez-vous
   - Débite le wallet
   - Crée le paiement (mode='wallet')
   - Génère la facture
   - Tout en transaction atomique
8. Redirection avec message incluant le montant débité

### Scénario 3 : Consultation de l'Historique
1. Patient va sur "Mon Portefeuille"
2. Voit son solde actuel
3. Scroll dans les transactions récentes
4. Clique sur "Voir tout" pour page complète
5. Peut filtrer par type/date (futur)
6. Voit détails de chaque transaction :
   - Référence unique
   - Type avec icône et couleur
   - Montant formaté
   - Solde avant/après
   - Date et heure

---

## 🎨 Design et UX

### Palette de Couleurs
- **Wallet principal** : Jaune/Orange (`from-yellow-500 to-orange-500`)
- **Rechargement** : Vert (`text-green-400`)
- **Paiement** : Rouge (`text-red-400`)
- **Remboursement** : Bleu (`text-blue-400`)
- **Retrait** : Orange (`text-orange-400`)

### Icônes
- 💳 Wallet : Carte avec rayures
- ➕ Rechargement : Flèche vers le haut
- ➖ Paiement : Flèche vers le bas
- 🔄 Remboursement : Flèche circulaire
- 💸 Retrait : Billets

### Effets Visuels
- **Backgrounds animés** avec gradients
- **Shadows néon** au hover (`shadow-[0_0_30px_rgba(...)]`)
- **Transitions fluides** sur tous les éléments
- **Badges de statut** colorés et arrondis
- **Cards avec backdrop-blur** pour effet verre

---

## 🔒 Sécurité

### Validations Backend
- Montant min/max sur recharges
- Vérification du solde avant débit
- Transactions atomiques (DB::transaction)
- Relations vérifiées (service_id → type_service_id)

### Gestion des Erreurs
- Try-catch avec rollback sur échec
- Messages utilisateur clairs
- Préservation des inputs en cas d'erreur
- Logs des exceptions

### Intégrité des Données
- Références uniques pour transactions
- Solde avant/après enregistré
- Timestamps automatiques
- Cascades on delete pour relations

---

## 📊 Statistiques et Rapports

### Disponibles dans le Dashboard Wallet
- **Total rechargé** : Somme de tous les rechargements
- **Total dépensé** : Somme de tous les paiements
- **Nombre de transactions** : Count total
- **Solde actuel** : En temps réel

### Futures Améliorations Possibles
- Export PDF/Excel des transactions
- Graphiques de l'évolution du solde
- Alertes de solde faible
- Historique par période (mensuel/annuel)
- Statistiques par type de service payé

---

## 🧪 Tests et Données de Test

### Script de Test
`tools/seed_wallet_test_data.php` :
- Crée des wallets pour tous les patients
- Génère 2-5 recharges aléatoires par patient
- Crée des paiements pour rendez-vous existants
- Affiche un résumé complet

### Usage
```bash
php tools/seed_wallet_test_data.php
```

### Résultat Attendu
- Wallets créés avec soldes variés
- Transactions de rechargement diverses
- Paiements de rendez-vous réels
- Historique complet pour chaque patient

---

## 🚀 Déploiement

### Migrations
```bash
php artisan migrate
```
Crée les tables `wallets` et `transactions`.

### Seeders
```bash
php artisan db:seed --class=MenuSeeder
```
Ajoute l'entrée "Mon Portefeuille" au menu.

### Données de Test
```bash
php tools/seed_wallet_test_data.php
```
Génère des données réalistes pour tests.

---

## 📝 Corrections Effectuées

### FCFA → FBU
Tous les affichages de devise ont été corrigés :
- ✅ Dashboard admin
- ✅ Page d'accueil publique
- ✅ Formulaire de rendez-vous
- ✅ Liste des paiements
- ✅ Liste des factures
- ✅ Wallet (rechargement, transactions)

### Combo Box Type de Services
- ❌ Problème : Alpine.js non chargé
- ✅ Solution : JavaScript vanilla avec événements
- ✅ Chargement dynamique via API fonctionnel
- ✅ Préservation de la sélection sur erreur

---

## 🎯 Prochaines Étapes

### Fonctionnalités Futures
1. **Remboursements** : Permettre aux admins de rembourser
2. **Retraits** : Permettre aux patients de retirer
3. **Virements** : Entre wallets (si pertinent)
4. **Notifications** : Email/SMS après transaction
5. **Limites** : Définir des plafonds par utilisateur
6. **KYC** : Vérification d'identité pour gros montants
7. **Multi-devises** : Support USD, EUR, etc.
8. **Cashback** : Programmes de fidélité
9. **Prélèvements automatiques** : Pour abonnements
10. **API publique** : Pour intégrations tierces

### Améliorations UX
- Dark/Light mode toggle
- Animations plus poussées (GSAP)
- Graphiques interactifs (Chart.js)
- Filtres avancés sur transactions
- Export de relevés
- Impression de reçus

---

## 🤝 Support

Pour toute question ou problème :
1. Vérifier les logs Laravel (`storage/logs/laravel.log`)
2. Vérifier la base de données SQLite
3. Tester avec `php artisan tinker`
4. Consulter la documentation Laravel

---

**Système développé avec ❤️ en FBU (Franc Burundais)**
