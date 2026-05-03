<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Customer details
            $table->string('account_number')->unique();
            $table->string('phone')->nullable();
            
            // Updated address fields
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();
            $table->string('zip_code')->nullable();
            
            // Keep old fields for backward compatibility
            $table->string('address')->nullable();
            $table->string('city')->nullable();

            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])
                  ->default('active');

            // Coordinates
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};