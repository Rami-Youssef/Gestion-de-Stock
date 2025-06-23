<?php

require 'vendor/autoload.php';

echo "Testing Export Dependencies...\n";

// Test Excel export dependencies
try {
    $reflection = new ReflectionClass('\Maatwebsite\Excel\Excel');
    echo "✓ Maatwebsite Excel package is loaded\n";
} catch (Exception $e) {
    echo "✗ Maatwebsite Excel package not found: " . $e->getMessage() . "\n";
}

// Test PDF export dependencies  
try {
    $reflection = new ReflectionClass('\Barryvdh\DomPDF\ServiceProvider');
    echo "✓ DomPDF package is loaded\n";
} catch (Exception $e) {
    echo "✗ DomPDF package not found: " . $e->getMessage() . "\n";
}

// Test if our export classes exist
$exportClasses = [
    'App\Exports\CategoriesExport',
    'App\Exports\ProduitsExport', 
    'App\Exports\MouvementsExport',
    'App\Exports\UsersExport'
];

foreach ($exportClasses as $class) {
    if (class_exists($class)) {
        echo "✓ $class exists\n";
    } else {
        echo "✗ $class not found\n";
    }
}

// Test if our trait exists
if (trait_exists('App\Http\Controllers\Traits\ExportableTrait')) {
    echo "✓ ExportableTrait exists\n";
} else {
    echo "✗ ExportableTrait not found\n";
}

echo "\nExport functionality test completed!\n";
