<?php

// Simple test to check if export functionality works
require 'vendor/autoload.php';

use Illuminate\Foundation\Application;

// Create Laravel application instance
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test if routes exist
echo "Testing Export Routes:\n\n";

$routes = [
    'categories.export.excel',
    'categories.export.pdf',
    'produits.export.excel', 
    'produits.export.pdf',
    'mouvements.export.excel',
    'mouvements.export.pdf',
    'user.export.excel',
    'user.export.pdf'
];

foreach ($routes as $routeName) {
    try {
        $url = route($routeName, ['scope' => 'current']);
        echo "✓ $routeName: $url\n";
    } catch (Exception $e) {
        echo "✗ $routeName: " . $e->getMessage() . "\n";
    }
}

// Test if export classes can be instantiated
echo "\nTesting Export Classes:\n\n";

$exportClasses = [
    'App\Exports\CategoriesExport',
    'App\Exports\ProduitsExport',
    'App\Exports\MouvementsExport', 
    'App\Exports\UsersExport'
];

foreach ($exportClasses as $class) {
    try {
        $request = new \Illuminate\Http\Request();
        $export = new $class($request, false);
        echo "✓ $class can be instantiated\n";
    } catch (Exception $e) {
        echo "✗ $class: " . $e->getMessage() . "\n";
    }
}

echo "\nTest completed!\n";
