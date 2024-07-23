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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('suppliesId');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('suppliesId')->references('id')->on('supplies')->onDelete('cascade'); // Change to product_id
            $table->string('order_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->string('status');
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
