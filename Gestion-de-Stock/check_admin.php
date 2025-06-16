<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find admin user
$admin = \App\Models\Utilisateur::where('email', 'admin@gestion-stock.com')->first();

echo "Admin User Information:\n";
if ($admin) {
    echo "ID: " . $admin->id . "\n";
    echo "Username: " . $admin->utilisateur . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
    echo "Password is hashed: " . (strlen($admin->motdepasse) > 20 ? "Yes" : "No") . "\n";
} else {
    echo "Admin user not found!\n";
}
