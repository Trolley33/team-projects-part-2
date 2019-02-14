@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>{{$parent->description}} | Sub Problems</h2>
	<!-- List of ub problems and Information about them -->
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Problem Type ID</th><th>Problem Type Name</th><th>Specialists</th><th>Active Problems</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($types as $problem_type)
			<tr>
				<td style="text-align: right;">{{sprintf("%04d", $problem_type->id)}}</td><td>{{$problem_type->description}}</td>
                <td style="text-align: right;">{{$problem_type->specialists}}</td>
                <td style="text-align: right;">{{$problem_type->problems}}</td>
				<td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
            @endforeach

        </tbody>
    </table>
	<div style="text-align: center;">
				<!-- Button to create new problem type -->
        <a class="blank" href="/problem_types/create?type={{$parent->id}}">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Problem Type
            </div>
        </a><br />
				<!-- Button to edit problem type -->
        <a class="blank" href="/problem_types/{{$parent->id}}/edit">
            <div class="bigbutton w3-card w3-button w3-row">
                Edit Information
            </div>
        </a><br />

				<!-- Button to Delete problem type -->
        {!!Form::open(['action' => ['ProblemTypeController@destroy', $parent->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete problem type? This action cannot be undone.');"]) !!}

        {{Form::hidden('_method', 'DELETE')}}

        {{Form::submit('Delete Problem Type', ['class'=> "bigbutton w3-card w3-button w3-row w3-red"])}}

        {!!Form::close() !!}
        <br />
	</div>
    <!-- specialists -->
    <h2 class="slideHeader">{{$parent->description}} | Specialists</h2>
    <div class="slideable">
        <table id='specialist-table' class="display cell-border stripe hover slidable" style="width:100%;">
            <thead>
                <tr>
                    <th>Employee ID</th><th>Full Name</th><th>---</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($specialists as $specialist)
                <tr>
                    <td>{{sprintf('%04d', $specialist->employee_id)}}</td>
                    <td>{{$specialist->forename}} {{$specialist->surname}}</td>
                    <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';" style="text-align: center;">
                        View/Edit
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr />
</div>

<script>
$(document).ready( function ()
{
    var table = $('#problem-table').DataTable();
    var table2 = $('#specialist-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
