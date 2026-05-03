<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_photos', function (Blueprint $table) {
            $table->id();

            // Link to technician_jobs table
            $table->unsignedBigInteger('job_id');
            $table->foreign('job_id')->references('id')->on('technician_jobs')->onDelete('cascade');

            // Track who uploaded
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            // Photo details
            $table->string('url');
            $table->string('thumb_url')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_photos');
    }
};
