@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>{{$department->name}}</h2>
	<table id='job-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<td>Job ID</td><td>Job Name</td>
				<td>No. of Employees</td><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($jobs as $job)
			<tr>
				<td>{{$job->id}}</td><td>{{$job->title}}</td>
				<td>{{$job->employees}}</td>
				<td class="editbutton" onclick="window.location.href = '/jobs/{{$job->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        {!!Form::open(['id'=>'createForm']) !!}

		{{Form::submit('Create New Job Title', ['class'=> "menu-item w3-card 	w3-button w3-row", 'style'=> 'width: 400px;'])}}
		{!!Form::close() !!}
        {!!Form::open(['id'=>'editForm']) !!}

		{{Form::submit('Edit Department', ['class'=> "menu-item w3-card 	w3-button w3-row", 'style'=> 'width: 400px;'])}}
		{!!Form::close() !!}
        {!!Form::open(['action' => ['DepartmentController@destroy', $department->id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>'deleteForm']) !!}

		{{Form::hidden('_method', 'DELETE')}}

		{{Form::submit('Delete Department', ['class'=> "menu-item w3-card 	w3-button w3-row w3-red", 'style'=> 'width: 400px;'])}}

		{!!Form::close() !!}
    <br />
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

var deptName = "";

$(document).ready( function () 
{
    var table = $('#job-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();

    department = <?php echo json_encode($department); ?>;

    if (department.id == 1)
    {
    	$('#createForm :input').prop('disabled', true);
    	$('#editForm :input').prop('disabled', true);
    	$('#deleteForm :input').prop('disabled', true);
    }
    else
    {
    	$('#createForm').submit(function () {
    		window.location.href = "/jobs/create?department={{$department->id}}";
    		return false;
    	});
    	$('#editForm').submit(function () {
    		window.location.href = "/departments/{{$department->id}}/edit";
    		return false;
    	});
    }

});

function confirmDelete()
{
	if (department.id == 1)
    {
    	return false;
    }
	return confirm("Really delete Department '" + department.name + "'? This action cannot be undone, and will also delete all related jobs.");
}
</script>

@endsection
