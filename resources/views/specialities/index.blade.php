@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Problem Types</h2>
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<td>Problem Type ID</td><td>Problem Type Name</td><td>Parent Problem Type</td><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($pt as $problem_type)
			<tr>
				<td>{{$problem_type->id1}}</td><td>{{$problem_type->desc1}}</td>
				@if (!is_null($problem_type->id2) && !is_null($problem_type->desc2))
					<td>{{$problem_type->desc2}}</td>
				@else
					<td>---</td>
				@endif
				<td class="editbutton" onclick="window.location.href = '/specialities/{{$problem_type->id1}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="/specialities/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Register New Software
            </div>
        </a><br />
	</div>
</div>

<style>
.editbutton:hover
{
	background-color: #BBBBBB !important;
	cursor: pointer;
}
</style>

<script>
$(document).ready( function () 
{
    var table = $('#problem-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
