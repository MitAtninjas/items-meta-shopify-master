<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreScriptTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_script_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedBigInteger('script_tag_id');
            $table->string('src');
            $table->mediumText('response');
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
        Schema::dropIfExists('store_script_tags');
    }
}
