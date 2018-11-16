@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="card">
                    <div class="card-img-top" style="height: 420px">
                        {!! Mapper::render() !!}
                    </div>
                </div>
            </div>
            <div class="col-12 mb-5">
                <div class="card">
                    <div class="card-header d-flex w-100 justify-content-between head-style">
                        <div style="font-size:1rem">Journey Planner.</div>
                        <div class="align-self-center">
                            <small>
                                From: {{$from->name}} - To: {{$to->name}}
                            </small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Depart</th>
                                    <th>Weather</th>
                                    <th>Free Bikes</th>
                                </tr>
                                @foreach($fromprediction as $frompre)
                                    <tr>
                                        <td>{{$frompre->name}}</td>
                                        <td>{{$frompre->weather_summary}}</td>
                                        <td>{{$frompre->average_free_bikes}}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <hr>
                            
                            <table class="table">
                                <tr>
                                    <th>Destination</th>
                                    <th>Weather</th>
                                    <th>Free Slots</th>
                                </tr>
                                @foreach($toprediction as $topre)
                                    <tr>
                                        <td>{{$topre->name}}</td>
                                        <td>{{$topre->weather_summary}}</td>
                                        <td>{{$topre->average_empty_slots}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>                        

                    </div>                            
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-5">
        <div class="card">
            <div class="card-header">
                Journey Details
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                                @isset($details['rows'][0]['elements'][0]['distance'])
                                    <tr>
                                        <td>Distance</td>
                                        <td>{{$details['rows'][0]['elements'][0]['distance']['text']}}</td>
                                    </tr>
                                @endisset
                                @isset($details['rows'][0]['elements'][0]['duration'])
                                    <tr>
                                        <td>Duration</td>
                                        <td>{{$details['rows'][0]['elements'][0]['duration']['text']}}</td>
                                    </tr>
                                @endisset
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
