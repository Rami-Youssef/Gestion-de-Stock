<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get a user to update
    $user = \App\Models\Utilisateur::first();
    echo "Found user: {$user->id} - {$user->utilisateur} - {$user->email}\n";
    
    // Try to update the user
    $result = $user->update([
        'utilisateur' => $user->utilisateur . '_updated',
        'email' => $user->email
    ]);
    
    echo "Update successful: " . ($result ? 'Yes' : 'No') . "\n";
    echo "Updated user: {$user->id} - {$user->utilisateur} - {$user->email}\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
