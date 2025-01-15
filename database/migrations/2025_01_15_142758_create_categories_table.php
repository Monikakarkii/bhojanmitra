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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key, bigint, unsigned, auto-increment
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->boolean('show_on_nav')->default(0); // TINYINT(1), default 0
            $table->boolean('show_on_home')->default(0); // TINYINT(1), default 0
            $table->integer('nav_index')->nullable();
            $table->integer('home_index')->nullable();
            $table->string('slug', 255)->unique(); // Add index for uniqueness
            $table->string('logo', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
