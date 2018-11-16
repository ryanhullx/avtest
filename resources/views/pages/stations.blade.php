@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-5">
        <div class="card">
            <div class="card-img-top" style="height: 420px">
                {!! Mapper::render() !!}
            </div>
        </div>
    </div>
    <div class="col-12 mb-5">
        <div class="card">
            <div class="card-header d-flex w-100 justify-content-between head-style">
                <div style="font-size:1rem">Journey Plan</div>
                <div class="align-self-center">
                    <small>
                        Last Updated: <strong>{{ \Carbon\Carbon::parse($city->latest)->format('M j, g:iA') }}</strong>
                    </small>
                </div>
            </div>
            <div class="card-body">

                @if(count($stations)>0)
                    <form action="/journey/plan" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="fromName">From</label>
                            <select class="form-control" id="fromName" name="fromId" required>
                                @foreach($stations as $station)
                                    <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="toName">to</label>
                            <select class="form-control" id="toName" name="toId" required>
                                @foreach($stations as $station)
                                    <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dayoftheweek">Departing Day of the Week</label>
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
                            <label for="houroftheday">Departing Hour of the day</label>
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

                        <button type="submit" class="btn btn-primary">Journey Plan</button>
                    </form>
                @endif

            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex w-100 justify-content-between head-style">
                <div style="font-size:1rem">{{$city->city}} Stations</div>
                <div class="align-self-center">
                    <small>
                        Last Updated: <strong>{{ \Carbon\Carbon::parse($city->latest)->format('M j, g:iA') }}</strong>
                    </small>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="stationData">
                    <thead>
                        <tr>                            
                            <td>City</td>
                            <td>Free Bikes</td>
                            <td>Empty Slots</td>
                            <td>Weather</td>
                            <td>°C</td>
                            <td>use</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
	$(function () {

		$('#stationData').DataTable({
			serverSide: true,
			processing: true,
            responsive: true,
            ordering: false,
			ajax: '/data/stations/{{$city->city}}',
			columns: [
				{data: 'name'},
				{data: 'free_bikes'},
				{data: 'empty_slots'},
				{data: 'weather_summary'},
				{data: 'weather_temperature'},
				{data: 'use', searchable: false}
            ],
			columnDefs: [
                {
			       'targets': 4,
			       'createdCell':  function (td, cellData, rowData, row, col) {
                        if(rowData['weather_temperature'] > 0) {
							$(td).html(rowData['weather_temperature']+'°C');
                       }
			       }
                }
            ]
		});

    });
    
    function goto(station) {
        window.location.replace("/city/{{$city->city}}/station/"+station);
    }

</script>
@endsection