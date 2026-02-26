<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number', 100)->unique();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('total_profit', 15, 2);
            $table->string('payment_method', 50);
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sale_id');
            $table->uuid('product_id');
            $table->decimal('quantity', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('cost_price_snapshot', 15, 2);
            $table->decimal('profit', 15, 2);
            $table->decimal('subtotal', 15, 2);

            $table->foreign('sale_id')
                ->references('id')
                ->on('sales')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
