<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$tables = DB::select('SHOW TABLES');
// The key is like Tables_in_oticket
$dbName = env('DB_DATABASE', 'oticket');
$key = 'Tables_in_' . $dbName;

echo "=== HASIL ANALISIS TABEL ===\n";
foreach ($tables as $t) {
    if(!isset($t->$key)) {
        // Fallback for different driver / keys
        $values = array_values((array)$t);
        $tableName = $values[0];
    } else {
        $tableName = $t->$key;
    }

    $count = DB::table($tableName)->count();
    
    // Check if it's a Laravel builtin table
    $isLaravelBuiltin = in_array($tableName, [
        'migrations',
        'password_reset_tokens',
        'password_resets', // older version
        'sessions',
        'failed_jobs',
        'jobs',
        'job_batches',
        'cache',
        'cache_locks',
        'personal_access_tokens'
    ]);

    // Simple heuristic to guess Model name
    // e.g., ticket_categories -> TicketCategory
    $modelName = Str::studly(Str::singular($tableName));
    $modelClass = "App\\Models\\$modelName";
    $hasModel = class_exists($modelClass);

    echo "- Table: $tableName\n";
    echo "  Rows: $count\n";
    echo "  Is Laravel Builtin: " . ($isLaravelBuiltin ? 'Yes' : 'No') . "\n";
    if (!$isLaravelBuiltin) {
        echo "  Has Model ($modelClass): " . ($hasModel ? 'Yes' : 'No') . "\n";
    }
    echo "\n";
}
