<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

use App\Station;

use Mapper;


class JourneyController extends Controller
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

    public function journyPlanner(Request $request)
    {

        return redirect('/journey/'.$request->input('fromId').'/'.$request->input('toId').'/'.$request->input('day').'/'.$request->input('hour'));

    }

    public function jounryPlanView($from,$to,$day,$hour)
    {
        $from = Station::find($from);
        $to = Station::find($to);

        Mapper::map($from->lat,$from->long,['zoom' => 12])->marker($to->lat,$to->long);        

        $API = new Client();            
        $response = $API->request('GET', 'https://maps.googleapis.com/maps/api/distancematrix/json?mode=bicycling&origins='.$from->lat.','.$from->long.'&destinations='.$to->lat.','.$to->long.'&key='.env('GOOGLE_API_KEY'));

        $details = json_decode($response->getbody(),true);
        
        $fromprediction = DB::table('historical_stations')
                    ->select('historical_stations.*',DB::raw('AVG(historical_stations.free_bikes) AS average_free_bikes'))
                    ->groupBy('weather_summary')
                    ->where('api_id',$from->api_id)
                    ->where('date_hour',$hour)
                    ->where('date_day_num_rep',$day)
                    ->get();

        $toprediction = DB::table('historical_stations')
                    ->select('historical_stations.*',DB::raw('AVG(historical_stations.empty_slots) AS average_empty_slots'))
                    ->groupBy('weather_summary')
                    ->where('api_id',$to->api_id)
                    ->where('date_hour',$hour)
                    ->where('date_day_num_rep',$day)
                    ->get();

        return view('pages.journey')->with('from',$from)->with('to',$to)->with('fromprediction',$fromprediction)->with('toprediction',$toprediction)->with('details',$details);
    }
}
