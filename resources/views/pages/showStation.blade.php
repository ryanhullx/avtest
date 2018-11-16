@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-8">
        <div class="row">
            <div class="col-12 mb-5">
                <div class="card">
                    <div class="card-img-top" style="height: 420px">
                        {!! Mapper::render() !!}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex w-100 justify-content-between head-style">
                        <div style="font-size:1rem">Station Bike Predictor.</div>
                        <div class="align-self-center">
                            <small>
                                Last Updated: <strong>{{ \Carbon\Carbon::parse($station->latest)->format('M j, g:iA') }}</strong>
                            </small>
                        </div>
                    </div>
                    <div class="card-body">
                            @if(count($prediction)>0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Weather Type</th>
                                            <th>Day</th>
                                            <th>Hour</th>
                                            <th>Average Bikes</th>
                                        </tr>
                                    </thead>
                                    @foreach($prediction as $predict)
                                        <tr>
                                            <td>{{$predict->weather_summary}}</td>
                                            <td>{{$predict->date_day_num_rep}}</td>
                                            <td>{{$predict->date_hour}}</td>
                                            <td>{{number_format($predict->average_free_bikes,0)}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                        <form action="/city/{{$station->city}}/station/{{$station->id}}/predict" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="dayoftheweek">Day of the Week</label>
                                <select class="form-control" id="dayoftheweek" name="day" required>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="houroftheday">Hour of the day</label>
                                <select class="form-control" id="houroftheday" name="hour" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Average Free Bikes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header">{{$station->name}}, Average Free Bikes</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Weather Type</th>
                            <th>Average Bikes</th>
                        </tr>
                    </thead>
                    @foreach($summary as $weather)
                        <tr>
                            <td>{{$weather->weather_summary}}</td>
                            <td>{{number_format($weather->average_free_bikes,0)}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
