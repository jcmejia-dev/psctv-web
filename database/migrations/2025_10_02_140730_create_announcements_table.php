<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');

            // Include all audiences (customers, technicians, staff, all)
            $table->enum('audience', ['customers', 'technicians', 'staff', 'all'])
                  ->default('all');

            // Priority and scheduling
            $table->enum('priority', ['normal', 'high'])->default('normal');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            // Status of announcement
            $table->enum('status', ['Scheduled', 'Active', 'Expired'])->default('Scheduled');

            // Created by user (admin/staff)
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
