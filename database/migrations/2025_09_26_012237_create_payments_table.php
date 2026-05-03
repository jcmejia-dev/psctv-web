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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->onDelete('cascade');

            // Payment details
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->string('method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference', 100)->nullable();

            // Payment status
            $table->enum('status', [
                'pending',
                'completed',
                'paid',
                'failed',
                'cancelled'
            ])->default('pending');

            // Optional metadata
            $table->timestamp('payment_date')->nullable();
            $table->json('payload')->nullable();

            // Track creator
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
