<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_id');
            $table->string('city');
            $table->integer('free_bikes');
            $table->integer('empty_slots');
            $table->string('long');
            $table->string('lat');
            $table->string('name');
            $table->datetime('last_updated');
            $table->string('weather_summary');
            $table->integer('weather_wind_speed')->nullable();
            $table->integer('weather_temperature');
            $table->integer('weather_rain_intensity')->nullable();
            $table->string('weather_rain_type')->nullable();
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
        Schema::dropIfExists('stations');
    }
}
