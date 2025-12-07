<?php
// Quick database check script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE CONNECTION CHECK ===\n\n";

try {
    // Test connection
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n\n";
} catch (\Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "=== CHECKING REQUIRED TABLES ===\n\n";

$tables = [
    'financial_budgets',
    'financial_transactions',
    'users',
];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "✓ Table '$table' exists\n";
        
        // Show columns
        $columns = Schema::getColumnListing($table);
        echo "  Columns: " . implode(', ', $columns) . "\n\n";
    } else {
        echo "✗ Table '$table' DOES NOT EXIST\n\n";
    }
}

echo "=== MIGRATION STATUS ===\n";
echo "Run: php artisan migrate\n";
echo "To run pending migrations\n";
?>
