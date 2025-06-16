<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// List all tables in the database
$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
echo "Current database tables:\n";

foreach ($tables as $table) {
    $tableName = reset($table);
    echo "- " . $tableName . "\n";
}
echo "\n";

// Verify categories
$categories = \App\Models\Categorie::all();
echo "Categories (" . $categories->count() . "):\n";
foreach ($categories as $category) {
    echo "ID: " . $category->id . " - Nom: " . $category->nom . "\n";
}
echo "\n";

// Verify utilisateurs
$utilisateurs = \App\Models\Utilisateur::all();
echo "Utilisateurs (" . $utilisateurs->count() . "):\n";
foreach ($utilisateurs as $utilisateur) {
    echo "ID: " . $utilisateur->id . " - Utilisateur: " . $utilisateur->utilisateur . " - Role: " . $utilisateur->role . "\n";
}
echo "\n";

// Verify produits
$produits = \App\Models\Produit::all();
echo "Produits (" . $produits->count() . "):\n";
foreach ($produits as $produit) {
    echo "ID: " . $produit->id . " - Nom: " . $produit->nom . " - Ref: " . $produit->reference . " - Prix: " . $produit->prix . "\n";
}
echo "\n";

// Verify mouvement_stocks
$mouvements = \App\Models\MouvementStock::all();
echo "Mouvements de Stock (" . $mouvements->count() . "):\n";
foreach ($mouvements as $mouvement) {
    echo "ID: " . $mouvement->id . " - Type: " . $mouvement->type . " - Qty: " . $mouvement->quantite . " - Date Cmd: " . $mouvement->date_cmd . "\n";
}
