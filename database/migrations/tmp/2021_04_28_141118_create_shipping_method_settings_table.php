<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_method_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_method_id');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
            $table->string('shipping_method_title');
            $table->float('shipping_cost');
            $table->boolean('show_preferred_delivery_date');
            $table->integer('no_of_service_points_to_show')->nullable();
            $table->boolean('enable_map');
            $table->string('partner_id')->nullable();
            $table->string('app_id')->nullable();
            $table->unsignedBigInteger('active_ants_shipping_method_id')->nullable();
            $table->integer('min_day')->nullable();
            $table->integer('max_day')->nullable();
            $table->text('country')->nullable();
            $table->integer('sort_order')->nullable();
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
        Schema::dropIfExists('shipping_method_metas');
    }
}
