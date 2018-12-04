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
					@if ($user->job_id == 1)
						Operator
					@elseif ($user->job_id == 2)
						Specialist
					@elseif ($user->job_id == 3)
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
        <a class="blank" href="/users/create/tech-support">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create User for Job
            </div>
        </a><br />
        <a class="blank" href="/users/create/caller">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Edit Job
            </div>
        </a><br />
        {!!Form::open(['action' => ['JobController@destroy', $user->job_id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>'deleteForm']) !!}

		{{Form::hidden('_method', 'DELETE')}}

		{{Form::submit('Delete Job', ['class'=> "menu-item w3-card 	w3-button w3-row w3-red", 'style'=> 'width: 400px;'])}}

		{!!Form::close() !!}
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
    	$('#deleteForm :input').prop('disabled', true);
    }
});

function confirmDelete()
{
	if (department.id == 1)
    {
    	return false;
    }
	return confirm("Really delete Job '" + job.title + "'? This action cannot be undone, and will also delete all related users.");
}
</script>

@endsection
