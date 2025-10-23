<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Service;
use App\Models\TypeService;
use App\Models\RendezVous;
use App\Models\Paiement;

echo "Creating test data...\n\n";

// Ensure roles exist
$adminRole = Role::firstOrCreate(['nom' => 'Admin'], ['description' => 'Administrateur']);
$medecinRole = Role::firstOrCreate(['nom' => 'Medecin'], ['description' => 'Médecin']);
$patientRole = Role::firstOrCreate(['nom' => 'Patient'], ['description' => 'Patient']);

// Create admin user
$admin = User::firstOrCreate(
    ['email' => 'admin@hospital.com'],
    [
        'name' => 'Admin Hospital',
        'password' => \Hash::make('password'),
        'role_id' => $adminRole->id,
    ]
);
echo "✓ Admin user: {$admin->email}\n";

// Create medecin user
$medecin = User::firstOrCreate(
    ['email' => 'medecin@hospital.com'],
    [
        'name' => 'Dr. Martin',
        'password' => \Hash::make('password'),
        'role_id' => $medecinRole->id,
    ]
);
echo "✓ Medecin user: {$medecin->email}\n";

// Create patient user
$patient = User::firstOrCreate(
    ['email' => 'patient@hospital.com'],
    [
        'name' => 'Patient Test',
        'password' => \Hash::make('password'),
        'role_id' => $patientRole->id,
    ]
);
echo "✓ Patient user: {$patient->email}\n\n";

// Create services
$consultationService = Service::firstOrCreate(
    ['nom' => 'Consultation Générale'],
    [
        'description' => 'Consultation médicale générale',
        'icone' => 'stethoscope',
    ]
);

$cardiologieService = Service::firstOrCreate(
    ['nom' => 'Cardiologie'],
    [
        'description' => 'Examens et consultations cardiologiques',
        'icone' => 'heart',
    ]
);

echo "✓ Services created\n";

// Create type services
$consultationGenerale = TypeService::firstOrCreate(
    [
        'service_id' => $consultationService->id,
        'nom' => 'Consultation Générale Standard'
    ],
    [
        'description' => 'Consultation médicale générale',
        'prix' => 15000,
        'duree_minutes' => 30,
    ]
);

$consultationUrgence = TypeService::firstOrCreate(
    [
        'service_id' => $consultationService->id,
        'nom' => 'Consultation Urgence'
    ],
    [
        'description' => 'Consultation médicale urgente',
        'prix' => 25000,
        'duree_minutes' => 20,
    ]
);

$ecg = TypeService::firstOrCreate(
    [
        'service_id' => $cardiologieService->id,
        'nom' => 'Électrocardiogramme (ECG)'
    ],
    [
        'description' => 'Examen ECG complet',
        'prix' => 30000,
        'duree_minutes' => 45,
    ]
);

echo "✓ Type services created\n";

// Create rendez-vous for patient
$rdv1 = RendezVous::firstOrCreate(
    [
        'utilisateur_id' => $patient->id,
        'date_rdv' => now()->addDays(2)->format('Y-m-d'),
        'heure_rdv' => '10:00',
    ],
    [
        'medecin_id' => $medecin->id,
        'type_service_id' => $consultationGenerale->id,
        'statut' => 'confirme',
        'notes' => 'Consultation de contrôle',
    ]
);

$rdv2 = RendezVous::firstOrCreate(
    [
        'utilisateur_id' => $patient->id,
        'date_rdv' => now()->subDays(7)->format('Y-m-d'),
        'heure_rdv' => '14:30',
    ],
    [
        'medecin_id' => $medecin->id,
        'type_service_id' => $ecg->id,
        'statut' => 'termine',
        'notes' => 'ECG de routine',
    ]
);

echo "✓ Rendez-vous created\n";

// Create paiements
$paiement1 = Paiement::firstOrCreate(
    ['rendez_vous_id' => $rdv2->id],
    [
        'montant' => 30000,
        'mode' => 'especes',
        'statut' => 'reussi',
        'date_paiement' => now()->subDays(7),
        'reference' => 'FAC-' . strtoupper(substr(uniqid(), -8)),
    ]
);

echo "✓ Paiement created\n";

echo "\n✅ Test data created successfully!\n";
echo "\nLogin credentials:\n";
echo "  Admin: admin@hospital.com / password\n";
echo "  Medecin: medecin@hospital.com / password\n";
echo "  Patient: patient@hospital.com / password\n";
