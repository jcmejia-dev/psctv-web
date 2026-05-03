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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // Role and preferences
            $table->enum('role', ['admin', 'staff', 'technician', 'customer'])->default('customer');
            $table->json('notification_preferences')->nullable();
            $table->string('language', 10)->default('en');

            // Laravel authentication columns
            $table->rememberToken(); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};