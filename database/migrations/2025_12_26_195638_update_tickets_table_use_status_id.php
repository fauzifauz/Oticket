<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Check if status column exists before dropping it
            if (Schema::hasColumn('tickets', 'status')) {
                $table->dropColumn('status');
            }
            
            // Check if status_id column doesn't exist before adding it
            if (!Schema::hasColumn('tickets', 'status_id')) {
                $table->foreignId('status_id')->after('category_id')->constrained('ticket_statuses');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->after('category_id');
            }
            
            if (Schema::hasColumn('tickets', 'status_id')) {
                $table->dropForeign(['status_id']);
                $table->dropColumn('status_id');
            }
        });
    }
};
