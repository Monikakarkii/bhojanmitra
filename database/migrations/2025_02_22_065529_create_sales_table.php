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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('order_id'); // Foreign key referencing Orders
            $table->decimal('total_amount', 10, 2); // Total amount from the order
            $table->enum('payment_method', ['cash', 'online']); // Payment method
            $table->timestamp('completed_at'); // Order completion timestamp
            $table->timestamps(); // Created and updated timestamps

            // Add a foreign key constraint for `order_id`
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
