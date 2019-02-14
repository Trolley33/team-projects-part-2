@extends('layouts.app')

@section('content')
<!-- Table of all problem types -->
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='pt-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>ID</th><th>Description</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($types as $pt)
			<tr>
				<td style="text-align: right;">{{sprintf("%04d", $pt->id)}}</td>
				<td>
				{{$pt->description}}
				</td>
				<td class="editbutton" onclick="window.location.href = '/review/problem_types/{{$pt->id}}';" style="text-align: center;">
					Review
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

</div>

<script>
$(document).ready( function () 
{
	// Initiliase datatable
    var table = $('#pt-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
