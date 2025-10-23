<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Patient\RendezVousController as PatientRdvController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;

// Find or create a Patient user
$patientRole = Role::firstOrCreate(['nom' => 'Patient'], ['description' => 'Patient']);
$patient = User::whereHas('role', function($q){ $q->where('nom','Patient'); })->first();
if (!$patient) {
    $patient = User::factory()->create([
        'name' => 'Test Patient',
        'email' => 'patient@example.com',
        'password' => \Hash::make('password'),
        'role_id' => $patientRole->id,
    ]);
    echo "Created patient user: {$patient->email}\n";
}

Auth::login($patient);

try {
    echo "Rendering patient dashboard...\n";
    echo view('patient.dashboard')->render();
    echo "\nRendering patient create page via controller...\n";
    // Ensure $errors exists for the view (when running outside an HTTP request)
    view()->share('errors', new ViewErrorBag());
    $ctrl = new PatientRdvController();
    $resp = $ctrl->create();
    echo $resp->render();
    echo "\nOK\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
