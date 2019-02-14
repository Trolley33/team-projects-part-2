@extends('layouts.app')

@section('content')
<!-- Table of all software -->
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='software-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Software ID</th><th>Name</th><th>Description</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($software as $s)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d', $s->id)}}</td><td>{{$s->name}}</td><td>{{$s->description}}</td>
				<td class="editbutton" onclick="window.location.href = '/review/software/{{$s->id}}';" style="text-align: center;">
					Review Software
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
    var table = $('#software-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
