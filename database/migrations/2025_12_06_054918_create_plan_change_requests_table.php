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
        Schema::create('plan_change_requests', function (Blueprint $table) {
            $table->id();
            
            // Subscription relationship
            $table->foreignId('subscription_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Package relationships
            $table->foreignId('old_package_id')
                  ->constrained('packages')
                  ->onDelete('cascade');
            
            $table->foreignId('new_package_id')
                  ->constrained('packages')
                  ->onDelete('cascade');
            
            // Plan change details
            $table->date('effective_date');
            $table->enum('effective_option', ['next_cycle', 'immediate'])
                  ->default('next_cycle');
            $table->boolean('requires_payment')->default(false);
            
            // Status
            $table->enum('status', ['pending', 'completed', 'cancelled'])
                  ->default('pending');
            
            // User references
            $table->foreignId('requested_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->foreignId('completed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->foreignId('cancelled_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Timestamps
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('subscription_id');
            $table->index('status');
            $table->index('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_change_requests');
    }
};