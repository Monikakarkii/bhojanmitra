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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable(); // Remove 'after' as it's not valid in create
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade'); // Assumes a `tables` table exists
            $table->enum('order_status', ['pending', 'preparing', 'ready_to_serve', 'paid', 'canceled'])->default('pending');
            $table->enum('payment_method', ['cash', 'online'])->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
