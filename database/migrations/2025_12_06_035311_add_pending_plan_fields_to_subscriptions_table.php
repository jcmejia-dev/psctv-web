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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('pending_plan_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->date('pending_plan_effective_date')->nullable();
            $table->text('pending_plan_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['pending_plan_id']);
            $table->dropColumn(['pending_plan_id', 'pending_plan_effective_date', 'pending_plan_notes']);
        });
    }
};
