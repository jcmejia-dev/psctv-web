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
        Schema::create('billing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->integer('grace_period_days')->default(3)->comment('Days after due date before late fees apply');
            $table->decimal('late_fee_amount', 8, 2)->default(5.00)->comment('Late fee amount per occurrence');
            $table->enum('late_fee_frequency', ['once', 'monthly'])->default('monthly')->comment('How often late fees are applied');
            $table->integer('suspension_days')->default(10)->comment('Days after due date to suspend service');
            $table->integer('disconnection_days')->default(30)->comment('Days after due date to disconnect service');
            $table->decimal('suspension_fee', 8, 2)->nullable()->comment('Fee to reactivate suspended service');
            $table->decimal('reconnection_fee', 8, 2)->nullable()->comment('Fee to reactivate disconnected service');
            $table->boolean('auto_apply_penalties')->default(true)->comment('Whether to automatically apply penalties');
            $table->timestamps();

            $table->unique('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_rules');
    }
};
