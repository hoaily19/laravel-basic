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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('categories_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);  // Giá cơ bản của sản phẩm
            $table->integer('stock');  // Tổng số lượng tồn kho
            $table->string('sku')->nullable()->unique();
            $table->string('image')->nullable();  // Ảnh chính của sản phẩm
            $table->json('images')->nullable();  // Các ảnh phụ của sản phẩm (mảng đường dẫn ảnh)
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};