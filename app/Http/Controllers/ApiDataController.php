<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\City;
use App\Station;
use App\HistoricalStation;

use Carbon\Carbon;

class ApiDataController extends Controller
{
    public function saveStationsLatestDataApi()
    {
        $this->getLatestCityBikesStations();
    }

    public function saveStationsHistoricalDataApi()
    {
        $this->saveCityBikeStations();
    }


    public function getCityBikesNetwork()
    {
        $API = new Client();            
        $response = $API->request('GET', 'https://api.citybik.es/v2/networks/');

        $networks = json_decode($response->getbody(),true);

        foreach($networks['networks'] as $network){

            $City = City::firstOrNew(['network_api_id' => $network['id']]);
            $City->network_api_id = $network['id'];
            $City->name = $network['name'];
            $City->city = $network['location']['city'];
            $City->country =  $network['location']['country'];
            $City->lat = $network['location']['latitude'];
            $City->long = $network['location']['longitude'];
            $City->api_source = 'citybikes';
            $City->save();

        }

        return 'done';
    }

    public function getLatestCityBikesStations()
    {
        $cities = City::where('active',1)->where('api_source','citybikes')->get();
        
        foreach($cities as $city){
            $API = new Client();  
            $stations = $API->request('GET', 'http://api.citybik.es/v2/networks/'.$city->network_api_id);
            $networkStations = json_decode($stations->getbody(),true);

            $weatherAPI = $API->request('GET','https://api.darksky.net/forecast/'.env('DARK_SKY_API_KEY').'/'.$city->lat.','.$city->long.'?exclude=minutely,hourly,daily,alerts,flags');
            $weatherAPIjson = json_decode($weatherAPI->getbody(),true);


            foreach($networkStations['network']['stations'] as $bikeStation){
                $station = Station::firstOrNew(['api_id' => $bikeStation['id']]);
                $station->api_id = $bikeStation['id'];
                $station->city = $city->city;
                $station->free_bikes = $bikeStation['free_bikes'];
                $station->empty_slots = empty($bikeStation['empty_slots']) ? 0 : $bikeStation['empty_slots'];
                $station->long = $bikeStation['longitude'];
                $station->lat = $bikeStation['latitude'];
                $station->name = $bikeStation['name'];
                $station->last_updated = Carbon::parse($bikeStation['timestamp'])->format('Y-m-d H:i:s');
                $station->weather_summary = $weatherAPIjson['currently']['summary'];
                $station->weather_wind_speed = $weatherAPIjson['currently']['windSpeed'];
                $station->weather_temperature = ($weatherAPIjson['currently']['temperature'] - 32) / 1.8;
                $station->weather_rain_intensity = $weatherAPIjson['currently']['precipIntensity'];
                $station->weather_rain_type = isset($weatherAPIjson['currently']['precipType']) ? $weatherAPIjson['currently']['precipType'] : NULL;
                $station->save();
            }

        }
        
    }

    public function saveCityBikeStations()
    {
        $cities = City::where('active',1)->where('api_source','citybikes')->get();
        
        foreach($cities as $city){
            $API = new Client();  
            $stations = $API->request('GET', 'http://api.citybik.es/v2/networks/'.$city->network_api_id);
            $networkStations = json_decode($stations->getbody(),true);

            $weatherAPI = $API->request('GET','https://api.darksky.net/forecast/'.env('DARK_SKY_API_KEY').'/'.$city->lat.','.$city->long.'?exclude=minutely,hourly,daily,alerts,flags');
            $weatherAPIjson = json_decode($weatherAPI->getbody(),true);


            foreach($networkStations['network']['stations'] as $bikeStation){
                $carbonTime = Carbon::parse($bikeStation['timestamp']);

                $station = new HistoricalStation;
                $station->api_id = $bikeStation['id'];
                $station->city = $city->city;
                $station->free_bikes = $bikeStation['free_bikes'];
                $station->empty_slots = empty($bikeStation['empty_slots']) ? 0 : $bikeStation['empty_slots'];
                $station->long = $bikeStation['longitude'];
                $station->lat = $bikeStation['latitude'];
                $station->name = $bikeStation['name'];
                $station->weather_summary = $weatherAPIjson['currently']['summary'];
                $station->weather_wind_speed = $weatherAPIjson['currently']['windSpeed'];
                $station->weather_temperature = ($weatherAPIjson['currently']['temperature'] - 32) / 1.8;
                $station->weather_rain_intensity = $weatherAPIjson['currently']['precipIntensity'];
                $station->weather_rain_type = isset($weatherAPIjson['currently']['precipType']) ? $weatherAPIjson['currently']['precipType'] : NULL;
                $station->date_year = $carbonTime->year;
                $station->date_month = $carbonTime->month;
                $station->date_day = $carbonTime->day;
                $station->date_day_num_rep = $carbonTime->dayOfWeekIso;
                $station->date_hour = $carbonTime->hour;
                $station->date_minute = $carbonTime->minute;
                $station->last_updated = $carbonTime->format('Y-m-d H:i:s');
                $station->save();
            }

        }
    }


    public function testit()
    {
        

        $API = new Client();            
        $response = $API->request('GET', 'http://api.citybik.es/v2/networks/dublinbikes');

        return json_decode($response->getbody(),true);
    }
}
