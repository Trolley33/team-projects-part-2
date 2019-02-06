@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<!-- Get department name based on department selected on the previous page -->
	<h2>{{$department->name}}</h2>
	<table id='job-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<td>Job ID</td><td>Job Name</td>
				<td>No. of Employees</td><th>---</th>
			</tr>
		</thead>
		<!-- Generated list of roles in the Department -->
		<tbody>
			@foreach ($jobs as $job)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d',$job->id)}}</td><td>{{$job->title}}</td>
				<td style="text-align: right;">{{$job->employees}}</td>
				<td class="editbutton" onclick="window.location.href = '/jobs/{{$job->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<!-- Form submitted when the View/Edit button is selected for an employee, that provides information about the relvant employee in the next page -->
	<div style="text-align: center;">
        {!!Form::open(['id'=>'createForm']) !!}

		{{Form::submit('Create New Job Title', ['class'=> "bigbutton w3-card 	w3-button w3-row"])}}
		{!!Form::close() !!}
        {!!Form::open(['id'=>'editForm']) !!}

		{{Form::submit('Edit Department', ['class'=> "bigbutton w3-card 	w3-button w3-row"])}}
		{!!Form::close() !!}
        {!!Form::open(['action' => ['DepartmentController@destroy', $department->id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>'deleteForm']) !!}

		{{Form::hidden('_method', 'DELETE')}}

		{{Form::submit('Delete Department', ['class'=> "bigbutton w3-card 	w3-button w3-row w3-red"])}}

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

		//If the department ID is 1, that is it's the Technical Support, then new roles cant be created, the name can't be changed and the department can't be deleted
    if (department.id == 1)
    {
    	$('#createForm :input').prop('disabled', true);
    	$('#editForm :input').prop('disabled', true);
    	$('#deleteForm :input').prop('disabled', true);

    	$('#createForm :input').hide();
    	$('#editForm :input').hide();
    	$('#deleteForm :input').hide();
    }
		//If department ID isn't 1, that is it's not Technical Support , then the user is directed to the relvant page with the department ID
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
//Pop up to confirm that the user wants to delete the department
function confirmDelete()
{
	if (department.id == 1)
    {
    	return false;
    }
	return confirm("Really delete Department '" + department.name + "'? This action cannot be undone; this will also delete all related jobs, and all employees with those jobs.");
}
</script>

@endsection
