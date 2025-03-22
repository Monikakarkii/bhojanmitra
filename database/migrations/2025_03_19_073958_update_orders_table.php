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
        Schema::table('orders', function (Blueprint $table) {
            // Modify order_status column to add 'served'
            $table->enum('order_status', ['pending', 'preparing', 'ready_to_serve', 'served', 'canceled'])
                  ->default('pending')
                  ->change();

            // Add new pay_status column
            $table->boolean('pay_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback: Remove pay_status
            $table->dropColumn('pay_status');

            // Revert order_status back to the original ENUM without 'served'
            $table->enum('order_status', ['pending', 'preparing', 'ready_to_serve', 'canceled'])
                  ->default('pending')
                  ->change();
        });
    }
};
