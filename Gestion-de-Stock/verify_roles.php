<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verify that only the three required roles exist
$roles = DB::table('utilisateurs')->select('role')->distinct()->get();
$validRoles = ['admin', 'super_admin', 'user'];
$allValid = true;

echo "Verifying roles in the database:\n";
foreach ($roles as $role) {
    echo "- Found role: " . $role->role;
    if (in_array($role->role, $validRoles)) {
        echo " ✓\n";
    } else {
        echo " ✗ (invalid role)\n";
        $allValid = false;
    }
}

if ($allValid) {
    echo "\nAll roles are valid. The system now has only the required roles: admin, super_admin, and user.\n";
} else {
    echo "\nThe system still has some invalid roles. Please review and fix manually.\n";
}

// Show the count of users for each role
echo "\nNumber of users with each role:\n";
$roleCounts = DB::table('utilisateurs')->select('role', DB::raw('count(*) as count'))->groupBy('role')->get();
foreach ($roleCounts as $roleCount) {
    echo "- " . $roleCount->role . ": " . $roleCount->count . "\n";
}
