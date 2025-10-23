<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Messages\MessageBag;
use Illuminate\Support\View\ViewErrorBag as LaravelViewErrorBag;

// ensure errors bag exists to satisfy @error directives
app('view')->share('errors', new LaravelViewErrorBag());

$user = User::where('email', 'admin@example.com')->first();
if (!$user) {
    echo "Admin user not found\n";
    exit(1);
}

// login the user
// programmatically set the current user for the request
app('auth')->setUser($user);

try {
    echo view('admin.services.create')->render();
    echo "\n=== RENDERED OK ===\n";
} catch (Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
