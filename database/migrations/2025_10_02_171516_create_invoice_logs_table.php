<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_logs', function (Blueprint $table) {
            $table->id();

            // Foreign Keys (nullable)
            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            // Log fields
            $table->text('message')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount_due', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_logs');
    }
};
