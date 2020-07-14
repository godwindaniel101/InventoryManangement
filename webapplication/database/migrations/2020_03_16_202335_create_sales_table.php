<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('product_quantity')->nullable();
            $table->string('product_cost')->nullable();
            $table->string('product_price')->nullable();
            $table->string('product_id')->nullable();
            $table->string('product_stock_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_discount')->nullable();
            $table->string('product_unitTotal')->nullable();
            $table->string('unitGross')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
