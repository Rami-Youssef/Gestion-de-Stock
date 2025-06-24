<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Final Verification:\n\n";

// 1. Check that only the three roles exist
$roles = DB::table('utilisateurs')->select('role')->distinct()->pluck('role')->toArray();
$validRoles = ['admin', 'super_admin', 'user'];

echo "1. Roles in the database: " . implode(', ', $roles) . "\n";
echo "   Valid roles: " . implode(', ', $validRoles) . "\n";

$invalidRoles = array_diff($roles, $validRoles);
if (count($invalidRoles) > 0) {
    echo "   WARNING: Found invalid roles: " . implode(', ', $invalidRoles) . "\n";
} else {
    echo "   ✓ All roles are valid\n";
}

// 2. View files updated correctly
$files = [
    'resources/views/users/create.blade.php',
    'resources/views/users/edit.blade.php',
    'resources/views/users/show.blade.php',
    'resources/views/users/index.blade.php',
];

echo "\n2. Checking view files for proper role display:\n";

$success = true;
foreach ($files as $file) {
    $content = file_get_contents(__DIR__ . '/' . $file);
    
    // Check for the old 'utilisateur' role value
    if (strpos($content, 'value="utilisateur"') !== false) {
        echo "   ✗ Found 'utilisateur' role value in $file\n";
        $success = false;
    }
    
    // In create and edit forms, check for the proper role options
    if (strpos($file, 'create.blade.php') !== false || strpos($file, 'edit.blade.php') !== false) {
        if (!preg_match('/value="user".+?Utilisateur/s', $content) || 
            !preg_match('/value="admin".+?Administrateur/s', $content) || 
            !preg_match('/value="super_admin".+?Super Administrateur/s', $content)) {
            echo "   ✗ Missing proper role options in $file\n";
            $success = false;
        }
    }
}

if ($success) {
    echo "   ✓ All view files have been updated correctly\n";
}
