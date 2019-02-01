@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='equipment-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Serial Number</th><th>Make</th><th>Model</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($equipment as $e)
			<tr>
				<td style="text-align: right;">{{$e->serial_no}}</td><td>{{$e->make}} {{$e->model}}</td>
				<td class="editbutton" onclick="window.location.href = '/review/equipment/{{$e->id}}';" style="text-align: center;">
					Review Equipment
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

</div>

<script>
$(document).ready( function () 
{
    var table = $('#equipment-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
