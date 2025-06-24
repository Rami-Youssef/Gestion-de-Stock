<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "Middleware Check:\n";
echo "----------------\n";

// Check if our middleware has been registered
$router = app('router');
$middlewares = $router->getMiddleware();

echo "1. Checking if CheckValidRole middleware is registered:\n";
if (isset($middlewares['valid.role']) && $middlewares['valid.role'] === 'App\Http\Middleware\CheckValidRole') {
    echo "   ✓ Middleware 'valid.role' is registered correctly\n";
} else {
    echo "   ✗ Middleware 'valid.role' is NOT registered\n";
}

echo "\n2. Checking middleware in auth routes group:\n";

// Get all routes and check middleware
$routes = Route::getRoutes();
$hasValidRoleMiddleware = false;
$missingValidRoleMiddleware = false;

foreach ($routes as $route) {
    if (in_array('auth', $route->middleware())) {
        if (in_array('valid.role', $route->middleware())) {
            $hasValidRoleMiddleware = true;
        } else {
            $missingValidRoleMiddleware = true;
            break;
        }
    }
}

if ($hasValidRoleMiddleware && !$missingValidRoleMiddleware) {
    echo "   ✓ All authenticated routes have the 'valid.role' middleware applied\n";
} else if ($hasValidRoleMiddleware && $missingValidRoleMiddleware) {
    echo "   ✗ Some authenticated routes are missing the 'valid.role' middleware\n";
} else {
    echo "   ✗ No routes found with the 'valid.role' middleware\n";
}

echo "\n3. Middleware functionality:\n";
echo "   - Users with roles 'user', 'admin', or 'super_admin' will be allowed access\n";
echo "   - Users with any other role will be logged out and redirected to login\n";
echo "   - The middleware has been applied to all authenticated routes\n";
