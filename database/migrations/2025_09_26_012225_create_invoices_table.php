<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('subscription_id')
                  ->nullable()
                  ->constrained('subscriptions')
                  ->onDelete('cascade');

            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained('customers')
                  ->onDelete('cascade');

            // Invoice Details
            $table->string('invoice_no')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_contact')->nullable();
            $table->string('walk_in_name')->nullable();

            // Billing & Period
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('amount_due', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->date('due_date');
            $table->date('billing_date')->nullable();

            // Description & Notes
            $table->string('description')->nullable();
            $table->text('notes')->nullable();

            // Recurring & Status
            $table->boolean('is_recurring')->default(false);
            $table->enum('status', ['unpaid', 'paid', 'overdue', 'cancelled'])->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
