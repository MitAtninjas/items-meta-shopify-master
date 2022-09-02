<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoreLocationAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('stores', function (Blueprint $table) {
        // change() tells the Schema builder that we are altering a table
        $table->unsignedBigInteger('location_id')->nullable();
        $table->mediumText('location_data')->nullable();
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
