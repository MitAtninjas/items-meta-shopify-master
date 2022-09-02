<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->string('point_id');
            $table->string('method_title');
            $table->string('office_name');
            $table->string('street');
            $table->string('number');
            $table->string('zip');
            $table->string('city');
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
        Schema::dropIfExists('pickup_points');
    }
}
