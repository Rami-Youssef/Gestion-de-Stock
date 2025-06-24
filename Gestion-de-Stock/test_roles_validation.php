<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;

echo "Testing validation for user roles:\n\n";

// Test validation with UserRequest rules
$rules = (new UserRequest())->rules();

echo "1. Current validation rules for 'role' field:\n";
print_r($rules['role']);
echo "\n";

// Test each of our expected roles
$validRoles = ['admin', 'super_admin', 'user'];
echo "2. Validating each of our required roles:\n";

foreach ($validRoles as $role) {
    $testData = [
        'utilisateur' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => $role
    ];
    
    $validator = Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "   ✗ Role '{$role}' fails validation with errors: " . implode(', ', $validator->errors()->all()) . "\n";
    } else {
        echo "   ✓ Role '{$role}' passes validation\n";
    }
}

echo "\n3. Testing invalid roles:\n";
$invalidRoles = ['utilisateur', 'gestionnaire', 'invalid'];

foreach ($invalidRoles as $role) {
    $testData = [
        'utilisateur' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => $role
    ];
    
    $validator = Validator::make($testData, $rules);
    
    if ($validator->fails()) {
        echo "   ✓ Invalid role '{$role}' correctly fails validation\n";
    } else {
        echo "   ✗ WARNING: Invalid role '{$role}' passes validation but shouldn't\n";
    }
}
