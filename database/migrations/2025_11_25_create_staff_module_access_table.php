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
        // Staff Modules Table
        Schema::create('staff_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Service Requests", "Customers", "Billing"
            $table->string('key')->unique(); // e.g., "service_requests", "customers", "billing"
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icon class
            $table->string('route_prefix')->nullable(); // e.g., "staff.service_requests"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Staff Module Permissions Table
        Schema::create('staff_module_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_module_id')->constrained('staff_modules')->onDelete('cascade');
            $table->string('action'); // e.g., "view", "create", "edit", "delete"
            $table->string('key')->unique(); // e.g., "service_requests.view"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // User Staff Module Access Table
        Schema::create('user_staff_module_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_module_id')->constrained('staff_modules')->onDelete('cascade');
            $table->boolean('has_access')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'staff_module_id']);
        });

        // User Staff Module Permissions Table (granular permissions)
        Schema::create('user_staff_module_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_module_permission_id')->constrained('staff_module_permissions')->onDelete('cascade');
            $table->boolean('granted')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'staff_module_permission_id'], 'user_staff_perm_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_staff_module_permissions');
        Schema::dropIfExists('user_staff_module_access');
        Schema::dropIfExists('staff_module_permissions');
        Schema::dropIfExists('staff_modules');
    }
};
