<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Admin\RendezVousController;
use Illuminate\Support\Facades\Auth;

// Find an admin user
$admin = User::whereHas('role', function($q){ $q->where('nom','Admin'); })->first();
if (!$admin) {
    echo "No admin user\n"; exit(1);
}

// Login user in the container
Auth::login($admin);

try {
    echo "Rendering admin rendez-vous index view...\n";
    $ctrl = new RendezVousController();
    $resp = $ctrl->index();
    echo $resp->render();
    echo "\nOK\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
