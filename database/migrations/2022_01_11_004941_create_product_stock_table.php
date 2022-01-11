<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock', function (Blueprint $table) {
             $table->bigIncrements('id');
             $table->unsignedBigInteger('product_id');
             $table->unsignedBigInteger('stock_id');
             $table->timestamps();
            
             $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
             });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stock');
    }
}
