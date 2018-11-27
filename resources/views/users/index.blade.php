@extends('layouts.app')

@section('content')
	<h1> Users! </h1>
	<table>
		<tr>
			<th>Username</th><th>Name</th><th>Job</th><th>Department</th><th>Phone Number</th><th>Access</th>
		</tr>

		@foreach ($info as $user)
		<tr>
			<td>{{$user->username}}</td><td>{{$user->forename}} {{$user->surname}}</td><td>{{$user->job_title}}</td><td>{{$user->department}}</td><td>{{$user->phone_number}}</td><td>{{$user->type}}</td>
		</tr>
		@endforeach

	</table>

@endsection