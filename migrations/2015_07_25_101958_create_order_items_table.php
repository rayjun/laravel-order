<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_items', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('good_id')->unsigned();
            $table->float('price', 10, 2);
            $table->integer('count');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->float('total_price', 10, 2);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_items');
	}

}
