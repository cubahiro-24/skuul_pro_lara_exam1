# Guide d'Utilisation - Partie Patient

## 📋 Fonctionnalités Patient

### 1. Tableau de Bord Patient (`/patient/dashboard`)
- **Statistiques** : Affiche le nombre total de RDV, en attente, confirmés et terminés
- **Prochains RDV** : Liste des rendez-vous à venir
- **Actions Rapides** : Liens vers prise de RDV, historique, et factures

### 2. Prendre Rendez-vous (`/patient/rendez-vous/create`)

#### Formulaire de Prise de RDV
Le formulaire comprend :
- **Sélection du Service** : Liste déroulante des services disponibles
  - Consultation Générale
  - Cardiologie
  - etc.
  
- **Type de Service** : Chargement dynamique selon le service sélectionné
  - Affiche le prix et la durée
  - Exemple : "Consultation Générale Standard - 15,000 FCFA (30 min)"

- **Date du RDV** : Calendrier (date >= aujourd'hui)

- **Heure du RDV** : Créneaux de 08:00 à 17:00 (par pas de 30 min)

- **Notes / Symptômes** : Zone de texte optionnelle

#### Fonctionnement
1. Le patient sélectionne un service
2. Les types de services se chargent automatiquement via AJAX (`/api/services/{id}/type-services`)
3. Le patient remplit les autres champs
4. À la soumission :
   - Validation côté serveur
   - Attribution automatique d'un médecin
   - Statut initial : "en_attente"
   - Redirection vers la liste des RDV avec message de succès

### 3. Mes Rendez-vous (`/patient/rendez-vous`)

#### Liste des RDV
- **Filtres** : Par statut (tous, en_attente, confirmé, terminé, annulé)
- **Informations affichées** :
  - Date et heure
  - Service et type de service
  - Médecin assigné
  - Statut avec badge coloré
  - Prix

#### Actions disponibles
- **Voir détails** : Bouton pour accéder au détail du RDV
- **Annuler** : Disponible uniquement pour les RDV "en_attente" ou "confirmé"

### 4. Mes Paiements (`/patient/paiements`)

#### Vue d'ensemble
- **Statistiques** :
  - Total payé (en FCFA)
  - Nombre de paiements
  - Date du dernier paiement

#### Tableau des paiements
Colonnes :
- Date et heure du paiement
- Service et type de service
- Médecin
- Montant (en vert)
- Méthode de paiement (badge)
- Numéro de référence

### 5. Mes Factures (`/patient/factures`)

#### Grille de factures
Chaque carte de facture affiche :
- Référence de la facture
- Service et type de service
- Médecin
- Date de paiement
- Montant total
- **Bouton "Télécharger PDF"** (à implémenter)

#### Informations importantes
- Les factures sont générées automatiquement après chaque paiement
- Format PDF téléchargeable
- Utile pour les remboursements d'assurance

## 🔐 Accès Patient

### Identifiants de Test
```
Email: patient@hospital.com
Password: password
```

### Rôles et Permissions
Le middleware `role:Patient` protège toutes les routes patient :
- Seuls les utilisateurs avec le rôle "Patient" peuvent accéder
- Redirection automatique après login vers le dashboard patient

## 🎨 Interface Utilisateur

### Design
- **Theme** : Cyberpunk/néon avec couleurs cyan et bleu
- **Framework CSS** : Tailwind CSS
- **JavaScript** : Alpine.js pour les interactions
- **Responsive** : Mobile-first design

### Composants Réutilisables
- `<x-card>` : Cartes avec effets de verre et bordures néon
- `<x-button>` : Boutons avec gradients et effets lumineux
- Sidebar futuriste avec icônes SVG
- Navbar avec recherche, notifications et menu utilisateur

## 🔄 Flux de Travail Patient

### Scénario Complet
1. **Connexion** → Patient se connecte avec ses identifiants
2. **Dashboard** → Voit ses statistiques et prochains RDV
3. **Prendre RDV** → Clique sur "Prendre Rendez-vous"
4. **Sélection** → Choisit service, type, date, heure
5. **Confirmation** → RDV créé avec statut "en_attente"
6. **Notification** → Message de succès affiché
7. **Attente** → Médecin ou admin confirme le RDV
8. **Consultation** → Patient se présente au RDV
9. **Paiement** → Effectue le paiement (géré par caissier)
10. **Facture** → Consulte et télécharge sa facture

## 🛠️ Développement

### Routes Patient
```php
Route::middleware(['auth', 'role:Patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', ...)->name('dashboard');
    Route::resource('rendez-vous', PatientRendezVousController::class);
    Route::get('/paiements', [PatientPaiementController::class, 'index'])->name('paiements.index');
    Route::get('/factures', [PatientPaiementController::class, 'factures'])->name('factures.index');
});
```

### API Endpoints
```
GET /api/services/{service}/type-services
```
Retourne les types de services pour un service donné (JSON).

### Modèles et Relations
```php
User -> hasMany(RendezVous, 'utilisateur_id')
RendezVous -> belongsTo(User, 'utilisateur_id')
RendezVous -> belongsTo(User, 'medecin_id')
RendezVous -> belongsTo(TypeService)
TypeService -> belongsTo(Service)
Paiement -> belongsTo(RendezVous)
```

## ✅ Tests

### Données de Test
Exécuter le script de création de données :
```bash
php tools/seed_test_data.php
```

Crée :
- 3 utilisateurs (admin, médecin, patient)
- 2 services avec types de services
- 2 rendez-vous pour le patient
- 1 paiement

### Validation
- ✅ Patient peut voir le dashboard
- ✅ Liste des services s'affiche sur la page de prise de RDV
- ✅ Types de services se chargent dynamiquement
- ✅ Paiements et factures affichent les bonnes données
- ✅ Routes protégées par rôle Patient

## 📝 Notes Techniques

### Colonnes Paiement
```
- montant (decimal)
- mode (enum: especes, carte, mobile)
- statut (enum: reussi, echoue, en_attente)
- date_paiement (datetime)
- reference (string, unique)
```

### Statuts Rendez-vous
```
- en_attente : RDV créé, en attente de confirmation
- confirme : RDV confirmé par médecin/admin
- termine : Consultation terminée
- annule : RDV annulé
```
