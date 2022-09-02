<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCustomerIdNullableStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->unsignedBigInteger('customer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
}
