<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStandardOptionFieldsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('standard_method_title')->nullable();
            $table->float('standard_method_cost')->nullable();
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
                'standard_method_title',
                'standard_method_cost'
            ]);
        });
    }
}
