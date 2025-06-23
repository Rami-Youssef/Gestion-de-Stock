<?php

echo "=== Export Functionality Diagnostic ===\n\n";

// Check if all required export classes exist
$exportClasses = [
    'CategoriesExport' => 'app/Exports/CategoriesExport.php',
    'ProduitsExport' => 'app/Exports/ProduitsExport.php', 
    'MouvementsExport' => 'app/Exports/MouvementsExport.php',
    'UsersExport' => 'app/Exports/UsersExport.php'
];

echo "1. Checking Export Classes:\n";
foreach ($exportClasses as $class => $file) {
    if (file_exists($file)) {
        echo "   ✓ $class exists\n";
    } else {
        echo "   ✗ $class missing\n";
    }
}

// Check PDF templates
$pdfTemplates = [
    'categories-pdf' => 'resources/views/exports/categories-pdf.blade.php',
    'produits-pdf' => 'resources/views/exports/produits-pdf.blade.php',
    'mouvements-pdf' => 'resources/views/exports/mouvements-pdf.blade.php',
    'users-pdf' => 'resources/views/exports/users-pdf.blade.php'
];

echo "\n2. Checking PDF Templates:\n";
foreach ($pdfTemplates as $name => $file) {
    if (file_exists($file)) {
        echo "   ✓ $name template exists\n";
    } else {
        echo "   ✗ $name template missing\n";
    }
}

// Check trait file
echo "\n3. Checking ExportableTrait:\n";
if (file_exists('app/Http/Controllers/Traits/ExportableTrait.php')) {
    echo "   ✓ ExportableTrait exists\n";
} else {
    echo "   ✗ ExportableTrait missing\n";
}

// Check controllers have been updated
$controllers = [
    'CategorieController' => 'app/Http/Controllers/CategorieController.php',
    'ProduitController' => 'app/Http/Controllers/ProduitController.php',
    'MouvementStockController' => 'app/Http/Controllers/MouvementStockController.php', 
    'UserController' => 'app/Http/Controllers/UserController.php'
];

echo "\n4. Checking Controller Updates:\n";
foreach ($controllers as $name => $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'ExportableTrait') !== false && strpos($content, 'exportExcel') !== false) {
            echo "   ✓ $name has export functionality\n";
        } else {
            echo "   ✗ $name missing export methods\n";
        }
    } else {
        echo "   ✗ $name file missing\n";
    }
}

// Check view updates
$views = [
    'categories/index' => 'resources/views/categories/index.blade.php',
    'produits/index' => 'resources/views/produits/index.blade.php',
    'mouvements/index' => 'resources/views/mouvements/index.blade.php',
    'users/index' => 'resources/views/users/index.blade.php'
];

echo "\n5. Checking View Updates:\n";
foreach ($views as $name => $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'export') !== false && strpos($content, 'dropdown') !== false) {
            echo "   ✓ $name has export UI\n";
        } else {
            echo "   ✗ $name missing export UI\n";
        }
    } else {
        echo "   ✗ $name file missing\n";
    }
}

// Check assets
echo "\n6. Checking Assets:\n";
if (file_exists('public/assets/css/custom.css')) {
    $css = file_get_contents('public/assets/css/custom.css');
    if (strpos($css, 'Export Functionality') !== false) {
        echo "   ✓ Custom CSS has export styles\n";
    } else {
        echo "   ✗ Custom CSS missing export styles\n";
    }
} else {
    echo "   ✗ Custom CSS file missing\n";
}

if (file_exists('public/assets/js/exports.js')) {
    echo "   ✓ Export JavaScript exists\n";
} else {
    echo "   ✗ Export JavaScript missing\n";
}

// Check routes
echo "\n7. Checking Routes:\n";
if (file_exists('routes/web.php')) {
    $routes = file_get_contents('routes/web.php');
    $exportRoutes = substr_count($routes, 'export');
    echo "   ✓ Found $exportRoutes export route definitions\n";
} else {
    echo "   ✗ Routes file missing\n";
}

echo "\n=== Diagnostic Complete ===\n";
