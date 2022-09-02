<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnvironmentAndApiKeyToShippingMethodSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_method_settings', function (Blueprint $table) {
            //
            $table->string('environment')->nullable();
            $table->string('api_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_method_settings', function (Blueprint $table) {
            //
        });
    }
}
