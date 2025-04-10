<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    public function up()
    {
        // Tạo bảng product_reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->integer('rating')->unsigned()->check('rating >= 1 AND rating <= 5'); 
            $table->text('comment')->nullable(); 
            $table->json('images')->nullable(); 
            $table->boolean('has_image')->default(false); 
            $table->boolean('has_video')->default(false); 
            $table->timestamps();
        });

        //review
        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('product_reviews')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['review_id', 'user_id']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('product_reviews');
    }
}