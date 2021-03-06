@extends('layouts.app')

@section('content')
<!-- Table of all specialists & operators -->
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='specialist-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Employee ID</th><th>Full Name</th><th>Phone Number</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($specialists as $s)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d',$s->employee_id)}}</td><td>{{$s->forename}} {{$s->surname}}</td><td>{{$s->phone_number}}</td>
				<td class="editbutton" onclick="window.location.href = '/review/specialists/{{$s->id}}';" style="text-align: center;">
					Review Employee
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

</div>

<script>
$(document).ready( function () 
{
	// Initialise datatable
    var table = $('#specialist-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
