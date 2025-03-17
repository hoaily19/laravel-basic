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
            $table->unsignedBigInteger('product_id');  // Liên kết với sản phẩm
            $table->string('size')->nullable();  // Kích thước
            $table->string('color')->nullable();  // Màu sắc
            $table->decimal('price', 10, 2)->nullable();  // Giá riêng cho biến thể
            $table->string('image')->nullable();  // Ảnh riêng cho biến thể
            $table->integer('stock')->default(0);  // Số lượng tồn kho cho biến thể
            $table->string('sku')->nullable()->unique();  // SKU riêng cho biến thể nếu cần
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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