@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<table id='equipment-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<td>Serial Number</td><td>Equipment Description</td><td>Equipment Model</td><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($equipment as $equip)
			<tr>
				<td>{{$equip->serial_number}}</td><td>{{$equip->description}}</td>
				<td>{{$equip->model}}</td>
				<td class="editbutton" onclick="window.location.href = '/equipment/{{$equip->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>

	<div style="text-align: center;">
        <a class="blank" href="/equipment/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Register New Equipment
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
    var table = $('#equipment-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
