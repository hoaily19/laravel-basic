<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->string('receiver_name'); // Tên người nhận
            $table->string('phone'); // Số điện thoại
            $table->string('province'); // Tỉnh/Thành phố
            $table->string('district'); // Quận/Huyện
            $table->string('ward'); // Xã/Phường
            $table->string('street'); // Địa chỉ cụ thể (số nhà, đường)
            $table->boolean('is_default')->default(false); // Địa chỉ mặc định
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}