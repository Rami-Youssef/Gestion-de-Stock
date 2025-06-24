<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

echo "Testing validation for user roles:\n\n";

// Define the rules manually - these should match the UserRequest rules
$rules = [
    'role' => ['required', Rule::in(['admin', 'super_admin', 'user'])]
];

// Test each of our expected roles
$validRoles = ['admin', 'super_admin', 'user'];
echo "1. Validating each of our required roles:\n";

foreach ($validRoles as $role) {
    $testData = [
        'role' => $role
    ];
    
    $validator = Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "   ✗ Role '{$role}' fails validation with errors: " . implode(', ', $validator->errors()->all()) . "\n";
    } else {
        echo "   ✓ Role '{$role}' passes validation\n";
    }
}

echo "\n2. Testing invalid roles:\n";
$invalidRoles = ['utilisateur', 'gestionnaire', 'invalid'];

foreach ($invalidRoles as $role) {
    $testData = [
        'role' => $role
    ];
    
    $validator = Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "   ✓ Invalid role '{$role}' correctly fails validation\n";
    } else {
        echo "   ✗ WARNING: Invalid role '{$role}' passes validation but shouldn't\n";
    }
}
