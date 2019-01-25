@extends('layouts.app')


@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>{{$job->title}}</h2>
	<table id='user-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Employee ID</th><th>Full Name</th><th>Phone Number</th><th>Account Type</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{{sprintf('%04d',$user->employee_id)}}</td><td>{{$user->forename}} {{$user->surname}}</td><td>{{$user->phone_number}}</td>
				<td>
					@if ($user->access_level == 1)
						Operator
					@elseif ($user->access_level == 2)
						Specialist
					@elseif ($user->access_level == 3)
						Analyst
					@else
						Caller
					@endif
				</td>
				<td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        {!!Form::open(['id'=>'createForm']) !!}
		{{Form::submit('Create User with Job Title', ['class'=> "bigbutton w3-card 	w3-button w3-row"])}}
		{!!Form::close() !!}

        {!!Form::open(['id'=>'editForm']) !!}
		{{Form::submit('Edit Job Title', ['class'=> "bigbutton w3-card 	w3-button w3-row"])}}
		{!!Form::close() !!}

        {!!Form::open(['action' => ['JobController@destroy', $job->id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>'deleteForm']) !!}

		{{Form::hidden('_method', 'DELETE')}}

		{{Form::submit('Delete Job Title', ['class'=> "bigbutton w3-card 	w3-button w3-row w3-red"])}}
		{!!Form::close() !!}
	</div>
</div>
<script>

var department;
var job;

$(document).ready( function () 
{
    var table = $('#user-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();

    department = <?php echo json_encode($department); ?>;
    job = <?php echo json_encode($job); ?>;

	if (department.id == 1)
    {
    	$('#createForm').submit(function () {
    		window.location.href = '/users/create/tech-support?department='+department.id+'&job='+job.id;
    		return false;
    	});
    	$('#editForm :input').prop('disabled', true);
    	$('#deleteForm :input').prop('disabled', true);
    }
    else
    {
    	$('#createForm').submit(function () {
    		window.location.href = '/users/create/caller?department='+department.id+'&job='+job.id;
    		return false;
    	});
    	$('#editForm').submit(function () {
    		window.location.href = "/jobs/"+ job.id +"/edit";
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
	return confirm("Really delete Job '" + job.title + "'? This action cannot be undone, and will also delete all related employees.");
}
</script>

@endsection
