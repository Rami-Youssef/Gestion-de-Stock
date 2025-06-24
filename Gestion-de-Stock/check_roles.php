<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$roles = DB::table('utilisateurs')->select('role')->distinct()->get();

echo "Current roles in the database:\n";
foreach ($roles as $role) {
    echo "- " . $role->role . "\n";
}

echo "\nNumber of users with each role:\n";
$roleCounts = DB::table('utilisateurs')->select('role', DB::raw('count(*) as count'))->groupBy('role')->get();
foreach ($roleCounts as $roleCount) {
    echo "- " . $roleCount->role . ": " . $roleCount->count . "\n";
}
