<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderUpdateProcessing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('orders_process', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('order_id');
            $table->mediumText('response');
            $table->boolean('status');
            $table->text('message');
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
        //
    }
}
