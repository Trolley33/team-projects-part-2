@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='job-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Job ID</th><th>Job Name</th><th>Department Name</th>
				<th>No. of Employees</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($jobs as $job)
			<tr>
				<td>{{$job->id}}</td><td>{{$job->title}}</td>
				<td>{{$job->name}}</td><td>{{$job->employees}}</td>
				<td class="editbutton" onclick="window.location.href = '/jobs/{{$job->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="/jobs/create">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Job Title
            </div>
        </a><br />
	</div>
</div>

<script>
$(document).ready( function () 
{
    var table = $('#job-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
