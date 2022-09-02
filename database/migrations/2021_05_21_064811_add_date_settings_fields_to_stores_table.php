<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateSettingsFieldsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->text('date_settings')->nullable();
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
            $table->dropColumn([
                'date_settings'
            ]);
        });
    }
}
