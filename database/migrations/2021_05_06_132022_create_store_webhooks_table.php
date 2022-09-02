<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_webhooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('webhook_id');
            $table->string('topic');
            $table->mediumText('response');
            $table->boolean('status');
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
        Schema::dropIfExists('store_webhooks');
    }
}
