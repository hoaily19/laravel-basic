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
            $table->string('code')->unique(); 
            $table->decimal('discount', 8, 2); 
            $table->enum('type', ['percentage', 'fixed']); 
            $table->decimal('min_order_amount', 8, 2)->nullable(); 
            $table->integer('max_uses')->nullable(); 
            $table->integer('used_count')->default(0); 
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expires_at')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}