<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();

            // Core info
            $table->string('technician_id')->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('service_area')->nullable();
            $table->date('date_hire')->nullable();
            $table->string('specialization')->nullable();

            // Emergency contact
            $table->string('emergency_name')->nullable();
            $table->string('emergency_phone')->nullable();

            // Status
            $table->string('status')->nullable();

            // Relation to users
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
