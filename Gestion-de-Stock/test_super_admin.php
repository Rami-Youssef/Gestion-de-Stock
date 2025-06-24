<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "SuperAdmin Middleware Check:\n";
echo "--------------------------\n";

// Check if our middleware has been registered
$router = app('router');
$middlewares = $router->getMiddleware();

echo "1. Checking if SuperAdminMiddleware is registered:\n";
if (isset($middlewares['super_admin']) && $middlewares['super_admin'] === 'App\Http\Middleware\SuperAdminMiddleware') {
    echo "   ✓ Middleware 'super_admin' is registered correctly\n";
} else {
    echo "   ✗ Middleware 'super_admin' is NOT registered\n";
}

echo "\n2. Checking if user routes have super_admin middleware:\n";

// Get all routes and check middleware
$routes = Route::getRoutes();
$userRoutesWithSuperAdmin = 0;
$userRoutesWithoutSuperAdmin = 0;

foreach ($routes as $route) {
    // Filter only our user management routes (not the api/user route)
    if (str_contains($route->uri(), 'user') && !str_contains($route->uri(), 'api/user') && !str_contains($route->uri(), 'login') && !str_contains($route->uri(), 'password')) {
        if (in_array('super_admin', $route->middleware())) {
            $userRoutesWithSuperAdmin++;
        } else {
            $userRoutesWithoutSuperAdmin++;
            echo "   ✗ Route '{$route->uri()}' doesn't have super_admin middleware\n";
        }
    }
}

if ($userRoutesWithSuperAdmin > 0 && $userRoutesWithoutSuperAdmin === 0) {
    echo "   ✓ All user routes have the 'super_admin' middleware applied\n";
} else if ($userRoutesWithSuperAdmin === 0) {
    echo "   ✗ No user routes found with the 'super_admin' middleware\n";
} else {
    echo "   ✗ Some user routes are missing the 'super_admin' middleware\n";
    echo "   Routes with super_admin: $userRoutesWithSuperAdmin\n";
    echo "   Routes without super_admin: $userRoutesWithoutSuperAdmin\n";
}

echo "\n3. Checking sidebar template:\n";
$sidebar = file_get_contents(__DIR__ . '/resources/views/layouts/navbars/sidebar.blade.php');

if (str_contains($sidebar, "@if(Auth::user()->role === 'super_admin')") && 
    str_contains($sidebar, "Gestion des Utilisateurs")) {
    echo "   ✓ Sidebar template has been updated to show user management only for super_admin users\n";
} else {
    echo "   ✗ Sidebar template has not been properly updated\n";
}
