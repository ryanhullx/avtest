<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_id');
            $table->string('city');
            $table->integer('free_bikes');
            $table->integer('empty_slots');
            $table->string('long');
            $table->string('lat');
            $table->string('name');
            $table->string('weather_summary');
            $table->integer('weather_wind_speed')->nullable();
            $table->integer('weather_temperature');
            $table->integer('weather_rain_intensity')->nullable();
            $table->string('weather_rain_type')->nullable();
            $table->integer('date_year');
            $table->integer('date_month');
            $table->integer('date_day');
            $table->integer('date_day_num_rep');
            $table->integer('date_hour');
            $table->integer('date_minute');
            $table->datetime('last_updated');
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
        Schema::dropIfExists('historical_stations');
    }
}
