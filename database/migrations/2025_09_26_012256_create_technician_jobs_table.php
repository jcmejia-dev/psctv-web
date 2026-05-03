<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('technician_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('customer_name');
            $table->string('service_type');
            $table->enum('status', ['pending', 'assigned', 'completed'])->default('pending');
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->onDelete('set null');
            $table->date('scheduled_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_jobs');
    }
};
