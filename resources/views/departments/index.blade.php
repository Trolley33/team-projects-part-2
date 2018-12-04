@extends('layouts.app')



@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='department-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<td>Department ID</td><td>Department Name</td>
				<td>No. of Employees</td><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($info as $department)
			<tr>
				<td>{{$department->id}}</td><td>{{$department->name}}</td>
				<td>{{$department->employees}}</td>
				<td class="editbutton" onclick="window.location.href = '/departments/{{$department->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="/departments/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Department
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
    var table = $('#department-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
