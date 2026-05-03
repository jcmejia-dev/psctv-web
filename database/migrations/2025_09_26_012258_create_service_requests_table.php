<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            // Customer (optional FK)
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

            // Basic customer snapshot
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Address + geolocation
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Service details
            $table->string('service_type', 100);
            $table->text('notes')->nullable();

            // Status
            $table->enum('status', ['pending', 'assigned', 'in-progress', 'completed'])
                  ->default('pending');

            // Technician assignment
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Report + rating
            $table->text('report')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->timestamp('rated_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Timestamps
            $table->timestamps();

            // Indexes for faster queries
            $table->index('status');
            $table->index('technician_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
