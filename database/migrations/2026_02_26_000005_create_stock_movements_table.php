<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('reference_type', 50);
            $table->uuid('reference_id')->nullable();
            $table->string('movement_type', 10);
            $table->decimal('quantity', 15, 2);
            $table->decimal('stock_before', 15, 2);
            $table->decimal('stock_after', 15, 2);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->index('reference_type');
            $table->index('reference_id');
            $table->index('movement_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
