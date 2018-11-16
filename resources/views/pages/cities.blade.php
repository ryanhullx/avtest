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

    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3>Cities</h3></div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table" id="cityData">
                        <thead>
                            <tr>                            
                                <td>City</td>
                                <td>Country</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
	$(function () {

		$('#cityData').DataTable({
			serverSide: true,
			processing: true,
            responsive: true,
            ordering:false,
			ajax: '/data/cities',
			columns: [
				{data: 'city'},
				{data: 'country'},
				{data: 'action', searchable: false},
				{data: 'use', searchable: false}
			]
		});

    });
    
    function goto(city) {
        window.location.replace("/city/"+city);
    }

</script>
@endsection
