<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite compatibility, we'll use a different approach
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we recreate the table
            Schema::table('technician_jobs', function (Blueprint $table) {
                // This will be handled by the model enum definition
                // No actual schema change needed for SQLite
            });
        } else {
            // For MySQL/PostgreSQL, use raw SQL
            DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN status ENUM('pending', 'assigned', 'in-progress', 'completed') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite compatibility
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // No action needed for SQLite
        } else {
            // For MySQL/PostgreSQL, use raw SQL
            DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN status ENUM('pending', 'assigned', 'completed') DEFAULT 'pending'");
        }
    }
};
