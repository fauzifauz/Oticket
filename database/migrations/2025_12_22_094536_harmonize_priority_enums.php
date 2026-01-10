<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('sla_rules')->where('priority', 'medium')->update(['priority' => 'normal']);
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low', 'normal', 'high', 'critical')");
        }
    }

    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low', 'medium', 'high', 'critical')");
        }
        
        DB::table('sla_rules')->where('priority', 'normal')->update(['priority' => 'medium']);
    }
};
