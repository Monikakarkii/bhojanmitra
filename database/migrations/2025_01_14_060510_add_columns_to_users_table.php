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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 255); // Add the 'role' column
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // Add the 'status' column
            $table->unsignedBigInteger('created_by')->nullable(); // Add the 'created_by' column
            $table->timestamp('last_login_at')->nullable(); // Add the 'last_login_at' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'created_by', 'last_login_at']);
        });
    }
};
