<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá (ví dụ: SALE10)
            $table->decimal('discount', 8, 2); // Giá trị giảm (ví dụ: 10% hoặc 50000 VNĐ)
            $table->enum('type', ['percentage', 'fixed']); // Loại giảm: phần trăm hoặc số tiền cố định
            $table->decimal('min_order_amount', 8, 2)->nullable(); // Giá trị đơn hàng tối thiểu để áp dụng
            $table->integer('max_uses')->nullable(); // Số lần sử dụng tối đa
            $table->integer('used_count')->default(0); // Số lần đã sử dụng
            $table->dateTime('expires_at')->nullable(); // Ngày hết hạn
            $table->boolean('is_active')->default(true); // Add this
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}