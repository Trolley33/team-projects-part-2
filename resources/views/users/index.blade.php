@extends('layouts.app')



@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='user-table' class="display cell-border stripe hover">
		<thead>
			<tr>
				<th>Employee ID</th><th>Full Name</th><th>Department</th><th>Phone Number</th><th>Account Type</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($info as $user)
			<tr>
				<td>{{sprintf('%04d',$user->employee_id)}}</td><td>{{$user->forename}} {{$user->surname}}</td><td>{{$user->name}}</td><td>{{$user->phone_number}}</td>
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
				<td class="editbutton" onclick="window.location.replace('/users/{{$user->id}}');" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="/users/create/tech-support">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Technical Support Account
            </div>
        </a><br />
        <a class="blank" href="/users/create/caller">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Caller Account
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
    var table = $('#user-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
