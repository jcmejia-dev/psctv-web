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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('free_trial_days')->default(30)->after('billing_cycle');
            $table->decimal('reconnection_fee', 10, 2)->default(0)->after('free_trial_days');
            $table->boolean('auto_disconnect_overdue')->default(true)->after('reconnection_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['free_trial_days', 'reconnection_fee', 'auto_disconnect_overdue']);
        });
    }
};
