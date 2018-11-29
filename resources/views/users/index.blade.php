@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='user-table' class="display cell-border stripe hover">
		<thead>
			<tr>
				<th>Employee ID</th><th>Username</th><th>Full Name</th><th>Account Type</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($info as $user)
			<tr>
				<td>{{$user->employee_id}}</td><td>{{$user->username}}</td><td>{{$user->forename}} {{$user->surname}}</td>
				<td>
					@if ($user->type == 1)
						Operator
					@elseif ($user->type == 2)
						Specialist
					@elseif ($user->type == 3)
						Analyst
					@else
						Caller
					@endif
				</td>
				<td><button class='editbutton w3-button' value='{{$user->id}}' style="margin: 0 auto;">Edit</button></td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="users/create/tech-support">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Technical Support Account
            </div>
        </a><br />
        <a class="blank" href="users/create/caller">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Caller Account
            </div>
        </a><br />
	</div>
</div>

<script>
$(document).ready( function () {
    $('#user-table').DataTable();
    $('.editbutton').click(function() {
    	window.location.href = '/users/' + $(this).attr('value') + "/edit";
    })
} );
</script>

@endsection
