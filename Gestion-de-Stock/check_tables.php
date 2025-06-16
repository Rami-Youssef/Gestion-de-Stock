<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all tables in the database
$tables = DB::select('SHOW TABLES');
echo "Current database tables:\n";

foreach ($tables as $table) {
    $tableName = reset($table);
    echo "- " . $tableName . "\n";
}
