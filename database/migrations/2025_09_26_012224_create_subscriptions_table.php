<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('customer_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('package_id')
                ->constrained()
                ->onDelete('cascade');

            // Subscription details
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_billing_date')->nullable();

            // Status
            $table->enum('status', ['active', 'pending', 'inactive', 'cancelled', 'expired'])
                  ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
