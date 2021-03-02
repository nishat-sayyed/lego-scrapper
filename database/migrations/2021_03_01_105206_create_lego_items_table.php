<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lego_items', function (Blueprint $table) {
            $table->id();
            $table->enum('marketplace', ['US', 'UK']);
            $table->string('name');
            $table->integer('number');
            $table->string('url');
            $table->double('price');
            $table->double('sale_price');
            $table->double('discount_amount');
            $table->double('discount_percentage');
            $table->timestamp('date_spotted');
            $table->enum('stock_status', ['Available', 'Out of stock']);
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
        Schema::dropIfExists('lego_items');
    }
}
