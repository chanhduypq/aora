<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->index();
            $table->string('title');
            $table->float('shop_price');
            $table->unsignedInteger('quantity');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('conf')->nullable();
            $table->string('style')->nullable();
            $table->string('shipping_weight')->nullable();
            $table->string('shipping_dimension')->nullable();
            $table->string('product_dimension')->nullable();
            $table->string('product_weight')->nullable();
            $table->text('image')->nullable();
            $table->text('url')->nullable();
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
        Schema::dropIfExists('products');
    }
}
