<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['api_id','name','city','country','lat','long','free_bikes','empty_slots','last_updated','weather_summary','weather_wind_speed','weather_temperature','weather_rain_intensity','weather_rain_type'];
}
