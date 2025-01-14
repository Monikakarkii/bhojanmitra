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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('app_name'); // The name of the app
            $table->string('app_logo')->nullable(); // The logo URL or file path
            $table->json('social_links')->nullable(); // Social media links stored as a JSON
            $table->text('quote')->nullable(); // A quote or tagline for the app
            $table->string('location')->nullable(); // App location or address
            $table->string('contact_number')->nullable(); // Contact phone number
            $table->string('contact_email')->nullable(); // Contact email address
            $table->string('theme_color_primary')->nullable(); // Primary theme color
            $table->string('theme_color_secondary')->nullable(); // Secondary theme color
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
