@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='software-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<!-- Table with list of Softwares and information about them -->
			<tr>
				<th>Software ID</th><th>Software Name</th><th>Software Description</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($software as $s)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d', $s->id)}}</td><td>{{$s->name}}</td>
				<td>{{$s->description}}</td>
				<td class="editbutton" onclick="window.location.href = '/software/{{$s->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
	<!-- Button to Register New Software -->
	<div style="text-align: center;">
        <a class="blank" href="/software/create">
            <div class="bigbutton w3-card w3-button w3-row">
                Register New Software
            </div>
        </a><br />
	</div>
</div>

<script>
$(document).ready( function ()
{

    var table = $('#software-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
