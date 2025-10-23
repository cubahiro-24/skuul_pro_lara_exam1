<?php
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\TypeService;

try {
    echo "Creating service...\n";
    $s = Service::create(['nom' => 'Test Service', 'description' => 'desc', 'icone' => 'icon']);
    $s->typeServices()->createMany([
        ['nom' => 'Sub 1', 'description' => 'd1', 'prix' => 10, 'duree_minutes' => 30],
        ['nom' => 'Sub 2', 'description' => 'd2', 'prix' => 20, 'duree_minutes' => 45],
    ]);
    echo "Created service id={$s->id} with type_services_count=" . $s->typeServices()->count() . "\n";

    echo "Updating service name...\n";
    $s->update(['nom' => 'Updated Service']);
    echo "Updated name: " . Service::find($s->id)->nom . "\n";

    echo "Updating first subservice...\n";
    $first = $s->typeServices()->first();
    $first->update(['nom' => 'Updated Sub']);
    echo "First sub now: " . $first->fresh()->nom . "\n";

    echo "Deleting second subservice...\n";
    $second = $s->typeServices()->skip(1)->first();
    if ($second) { $second->delete(); }
    echo "Type services count after delete: " . $s->typeServices()->count() . "\n";

    echo "Deleting service...\n";
    $s->delete();
    echo "Service exists after delete? " . (Service::find($s->id) ? 'yes' : 'no') . "\n";

    echo "OK\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
