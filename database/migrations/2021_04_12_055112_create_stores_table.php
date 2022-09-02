<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('store_url');
            $table->string('store_type');
            $table->string('app_name');
            $table->string('api_key');
            $table->string('api_password');
            $table->string('shared_secret');
            $table->string('api_version');
            $table->boolean('enabled');
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
        Schema::dropIfExists('stores');
    }
}
