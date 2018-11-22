@extends('layouts.app')

@section('content')
<div class="w3-container w3-display-middle" style="width:80%">
		<header class="w3-card w3-light-grey w3-padding-small">
		<img src = "Team-Login.png" width=80 class = "w3-left w3-padding-16 w3-margin-right "></img>
		<h2 class = "w3-margin-right">Name: {{$name}}</h2>
		<h3>ID: 123123<h3>
		</header>
		<br />
		<div class="w3-card w3-padding-small w3-light-grey">
		<h2>Problem History</h2>
			<div>
				<div class="w3-padding-small w3-white" style='text-align:center'>
					<table>
						<tr>
							<th> Timestamp </th>
							<th> Caller ID </th>
							<th> Problem Type </th>
              <th> Notes </th>
              <th></th>
						</tr>
						<tr id="1" class="w3-hover-light-grey solve">
							<td> 2018-10-24 17:55 </td>
							<td> 0001 </td>
							<td> Unresponsive Keyboard </td>
							<td> Not able to type on keyboard properly. </td>
              <td><button class="w3-button fill">Edit</button></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<button class="w3-button w3-block w3-dark-grey"></button>
</div>
@endsection