<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "Permission Check:\n";
echo "---------------\n";

// Get all routes with their middleware
$routes = Route::getRoutes();
$allRoutes = [];
$standardRoutes = [];
$adminRoutes = [];
$superAdminRoutes = [];

// Categorize routes by middleware
foreach ($routes as $route) {
    if (strpos($route->uri(), 'api/') === 0 || strpos($route->uri(), '_') === 0) {
        continue; // Skip API and special routes
    }
    
    $middlewares = $route->middleware();
    
    if (in_array('super_admin', $middlewares)) {
        $superAdminRoutes[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    } elseif (in_array('admin', $middlewares)) {
        $adminRoutes[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    } elseif (in_array('valid.role', $middlewares)) {
        $standardRoutes[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    }
}

// Check for delete routes in standard middleware
$deleteRoutesInStandard = false;
$modifyRoutesInStandard = false;

foreach ($standardRoutes as $route) {
    if (strpos($route['method'], 'DELETE') !== false) {
        $deleteRoutesInStandard = true;
        echo "❌ DELETE route found in standard middleware: {$route['method']} {$route['uri']}\n";
    }
    
    if ((strpos($route['uri'], 'edit') !== false || 
        (strpos($route['method'], 'PUT') !== false || strpos($route['method'], 'PATCH') !== false)) 
        && !strpos($route['uri'], 'profile')) {
        $modifyRoutesInStandard = true;
        echo "❌ Edit/Modify route found in standard middleware: {$route['method']} {$route['uri']}\n";
    }
}

// Summary
echo "\n1. Routes Summary:\n";
echo "   Standard Routes (all valid roles): " . count($standardRoutes) . "\n";
echo "   Admin Routes (admin & super_admin): " . count($adminRoutes) . "\n";
echo "   Super Admin Routes: " . count($superAdminRoutes) . "\n";

echo "\n2. Permission Checks:\n";
if (!$deleteRoutesInStandard) {
    echo "   ✓ No DELETE routes in standard middleware\n";
} else {
    echo "   ✗ Found DELETE routes in standard middleware\n";
}

if (!$modifyRoutesInStandard) {
    echo "   ✓ No Edit/Modify routes in standard middleware\n";
} else {
    echo "   ✗ Found Edit/Modify routes in standard middleware\n";
}

// Check for delete routes in super_admin middleware
$deleteRoutesInSuperAdmin = false;
foreach ($superAdminRoutes as $route) {
    if (strpos($route['method'], 'DELETE') !== false) {
        $deleteRoutesInSuperAdmin = true;
        break;
    }
}

if ($deleteRoutesInSuperAdmin) {
    echo "   ✓ DELETE routes are in super_admin middleware\n";
} else {
    echo "   ✗ No DELETE routes found in super_admin middleware\n";
}

// Check for modify routes in admin middleware
$modifyRoutesInAdmin = false;
foreach ($adminRoutes as $route) {
    if (strpos($route['uri'], 'edit') !== false || 
        (strpos($route['method'], 'PUT') !== false || strpos($route['method'], 'PATCH') !== false)) {
        $modifyRoutesInAdmin = true;
        break;
    }
}

if ($modifyRoutesInAdmin) {
    echo "   ✓ Edit/Modify routes are in admin middleware\n";
} else {
    echo "   ✗ No Edit/Modify routes found in admin middleware\n";
}

// List super admin routes
echo "\nSuper Admin Routes (Delete operations & User management):\n";
foreach ($superAdminRoutes as $route) {
    echo "   - {$route['method']} {$route['uri']} ({$route['name']})\n";
}

// List admin routes
echo "\nAdmin Routes (Edit operations):\n";
foreach ($adminRoutes as $route) {
    echo "   - {$route['method']} {$route['uri']} ({$route['name']})\n";
}
