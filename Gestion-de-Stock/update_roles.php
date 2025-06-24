<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Show current counts before update
echo "Before update:\n";
$roleCounts = DB::table('utilisateurs')->select('role', DB::raw('count(*) as count'))->groupBy('role')->get();
foreach ($roleCounts as $roleCount) {
    echo "- " . $roleCount->role . ": " . $roleCount->count . "\n";
}

// Update roles according to the requirements
// Convert 'utilisateur' to 'user'
$updated1 = DB::table('utilisateurs')->where('role', 'utilisateur')->update(['role' => 'user']);
// Convert 'gestionnaire' to 'user'
$updated2 = DB::table('utilisateurs')->where('role', 'gestionnaire')->update(['role' => 'user']);
// Convert any other roles (except admin, super_admin, user) to 'user'
$updated3 = DB::table('utilisateurs')
    ->whereNotIn('role', ['admin', 'super_admin', 'user'])
    ->where('role', '!=', 'utilisateur')  // already handled
    ->where('role', '!=', 'gestionnaire') // already handled
    ->update(['role' => 'user']);

// Show results after update
echo "\nRoles updated:\n";
echo "- 'utilisateur' to 'user': $updated1 records\n";
echo "- 'gestionnaire' to 'user': $updated2 records\n";
echo "- Other roles to 'user': $updated3 records\n";

// Show final counts
echo "\nAfter update:\n";
$roleCounts = DB::table('utilisateurs')->select('role', DB::raw('count(*) as count'))->groupBy('role')->get();
foreach ($roleCounts as $roleCount) {
    echo "- " . $roleCount->role . ": " . $roleCount->count . "\n";
}
