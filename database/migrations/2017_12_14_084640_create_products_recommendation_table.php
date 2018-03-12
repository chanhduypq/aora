<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsRecommendationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_recommendation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->string('title');
            $table->float('rate')->default(0);
            $table->float('shop_price');
            $table->text('image')->nullable();
            $table->text('url')->nullable();
            $table->float('order_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_recommendation');
    }
}
