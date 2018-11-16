<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\City;
use App\Station;
use App\HistoricalStation;

use Mapper;

class StationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($city)
    {        
        $city = City::where('city',$city)->first();
        $stations = Station::where('city',$city->city)->get();

        $i = 0;
        foreach($stations as $station){
            if($i == 0){
                Mapper::map($station->lat,$station->long,['eventClick' => 'goto("'.$station->id.'");']);
                $city->latest = $station->last_updated;
            } else {
                Mapper::marker($station->lat,$station->long,['eventClick' => 'goto("'.$station->id.'");']);
            }
            $i++;            
        }

        return view('pages.stations')->with('city',$city)->with('stations',$stations);
    }

    public function stationData(Datatables $datatables, $city)
    {
        $stations = Station::where('city',$city)->get();
        
        return $datatables->of($stations)->addColumn('use',
                                            function ($station) {
                                                return '<a href="city/'.$station->city.'/station/' . $station->id . '/" class="btn btn-primary btn-block btn-sm">Station Data</a>';                                            
                                            })
                                            ->rawColumns(['use'])
                                            ->make(true);
    }

    public function stationShow($city,$id,$day = NULL,$hour = NULL)
    {
        $station = Station::find($id);

        Mapper::map($station->lat, $station->long,['zoom' => 15]);

        if(isset($day)){
            $prediction = DB::table('historical_stations')
                    ->select('historical_stations.*',DB::raw('AVG(historical_stations.free_bikes) AS average_free_bikes'))
                    ->groupBy('weather_summary')
                    ->where('api_id',$station->api_id)
                    ->where('date_hour',$hour)
                    ->where('date_day_num_rep',$day)
                    ->get();
        } else {
            $prediction = [];
        }
        
        $summary = DB::table('historical_stations')
                    ->select('historical_stations.*',DB::raw('AVG(historical_stations.free_bikes) AS average_free_bikes'))
                    ->groupBy('weather_summary')
                    ->where('api_id',$station->api_id)
                    ->get();

        return view('pages.showStation')->with('summary',$summary)->with('station',$station)->with('prediction',$prediction);
    }

    public function stationPredict(Request $request,$city,$id)
    {
        $day = $request->input('day');
        $hour = $request->input('hour');
        
        return redirect('/city/'.$city.'/station/'.$id.'/'.$day.'/'.$hour);
    }
}
