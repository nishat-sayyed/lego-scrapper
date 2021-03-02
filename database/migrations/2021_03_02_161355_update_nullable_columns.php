<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lego_items', function (Blueprint $table) {
            $table->integer('sale_price')->nullable()->change();
            $table->integer('discount_amount')->nullable()->change();
            $table->integer('discount_percentage')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lego_items', function (Blueprint $table) {
            $table->integer('sale_price')->nullable(false)->change();
            $table->integer('discount_amount')->nullable(false)->change();
            $table->integer('discount_percentage')->nullable(false)->change();
        });
    }
}
