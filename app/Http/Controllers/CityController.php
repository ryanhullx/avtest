<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use App\City;

use Mapper;

class CityController extends Controller
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


    public function index()
    {
        $cities = City::where('active',1)->get();

        $i = 0;
        foreach($cities as $city){
            if($i == 0){
                Mapper::map($city->lat,$city->long,['draggable' => true, 'eventClick' => 'goto("'.$city->city.'");']);
            } else {
                Mapper::marker($city->lat,$city->long,['draggable' => true, 'eventClick' => 'goto("'.$city->city.'");']);
            }
            $i++;            
        }

        return view('pages.cities');
    }

    public function cityData(Datatables $datatables)
    {

        $cities = City::orderBy('active','DESC')->get();
        
        return $datatables->of($cities)->addColumn('action',
                                        function ($city) {
                                            if($city->active == 1){
                                                $state = "btn-success";
                                                $text = "Activated";
                                                $value = 0;
                                            } else {
                                                $state = "btn-danger";
                                                $text = "De-Activated";
                                                $value = 1;
                                            }
                                            return '<a href="/alter/' . $city->id . '/'.$value.'" class="btn '.$state.' btn-block btn-sm">'.$text.'</a>';
                                        })
                                        ->addColumn('use',
                                            function ($city) {
                                                return '<a href="/city/' . $city->city . '/" class="btn btn-primary btn-block btn-sm">View Stations</a>';
                                            
                                        })
                                        ->rawColumns(['action','use'])
                                        ->make(true);
    }

    public function alterStatus($id,$value)
    {
        $city = City::find($id);
        $city->active = $value;
        $city->save();

        return redirect()->back();
    }
}
