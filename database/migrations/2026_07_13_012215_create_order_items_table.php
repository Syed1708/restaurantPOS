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
        // database/migrations/xxxx_xx_xx_create_order_items_table.php
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onSetNull();
            $table->string('product_name'); // Store name at time of sale
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2); // Price at time of sale
            $table->decimal('vat_rate', 4, 2);    // VAT rate at time of sale
            $table->decimal('subtotal', 10, 2);  // total for this item row
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
