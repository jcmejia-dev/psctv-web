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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Change the enum to include 'pending'
            $table->enum('service_status', ['active', 'disconnected', 'suspended', 'pending'])->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('service_status', ['active', 'disconnected', 'suspended'])->default('active')->change();
        });
    }
};
