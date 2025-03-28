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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');  
            $table->unsignedBigInteger('size_id')->nullable();  // Liên kết với bảng sizes
            $table->unsignedBigInteger('color_id')->nullable(); // Liên kết với bảng colors
            $table->decimal('price', 10, 2)->nullable();  
            $table->string('image')->nullable();  
            $table->integer('stock')->default(0);  
            $table->string('sku')->nullable()->unique();  
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
