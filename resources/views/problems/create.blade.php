@extends('layouts.app')

@section('content')

<style>
.call_menu{
	border-radius: 2px;
	margin: 30px auto;
	width: 80%;
	min-width: 600px;
	background-color: white;
	margin-bottom: 100px;
}
.technician-select{
	width: 60%;
}
textarea{
	width: 100%;
}
table{
	width: 90%;
	margin-left: 5%;
}
td{
	padding: 10px;
}
th{
	padding: 10px;
	background-color: lightgrey;
}
</style>

<div class="call_menu w3-center w3-padding w3-light-grey">
	<div>
		<div class="w3-padding-large w3-white" style="text-align:center">
			<h2>Problem</h2>
			<table>
				<tbody>
					<tr id="2" class="w3-hover-light-grey solve">
						<th>Problem Type</th>
						<td>
						<select id='problem-select' class="problem-select" multiple="multiple" required>
							
						</select>
					</td>
					</tr>
					<tr id = "3" class="w3-hover-light-grey solve">
						<th>Hardware Serial#</th>
						<td>1234</td>
					</tr>
					<tr id = "4" class="w3-hover-light-grey solve">
						<th>Operating System</th>
						<td>N/A</td>
					</tr>
					<tr id = "5" class="w3-hover-light-grey solve">
						<th>Software Affected</th>
						<td>Microsoft Word</td>
					</tr>
					<tr id = "5" class="w3-hover-light-grey solve">
						<th>Status</th>
						<td id = "status" class="w3-red" >Unsolved</td>
					</tr>
				</tbody>
			</table>

			<div id = "solve" class="w3-padding-large w3-white" style="text-align:center">
				<input onclick = "solve()" class="w3-card w3-btn w3-row w3-light-grey" value="Solve">
			</div>

			<br />

			<h2>Calls</h2>
			<table class = "w3-center">
				<tbody>
					<tr w3-light-grey>
						<th> Caller Name </th>
						<th> Time </th>
						<th> Reason </th>
					</tr>

                    <tr id="_1" class="w3-hover-light-grey solve">
						<td> Emma </td>
						<td> 2018-11-12 09:30</td>
						<td> Can't print things from word. </td>
					</tr>


					<!--
                    Not Needed On Technician Page
                    <tr id = "addCall" class = "w3-hover-light-grey solve">
						<td><input id = 'callerName' type = "text"></td>
						<td><p id = "date"></p></td>
						<td><textarea id = 'callerReason' rows = 3></textarea></td>
					<tr>
                    -->


			</tbody>
		</table>

			<br />

			<div id = "newCallButton" class="w3-padding-large w3-white" style="text-align:center">
				<input onclick = "showAddCall()" class="w3-card w3-btn w3-row w3-light-grey" value="New Call + ">
			</div>

			<!-- <div id = "submitCallButton" class="w3-padding-large w3-white" style="text-align:center">
				<input onclick = "submitCall()" class="w3-card w3-btn w3-row w3-light-grey" value="Submit">
			</div> -->

			<br />

			<!-- <h2>Assign Technician</h2>
              <select class="technician-select" required>
                <optgroup label=" Name &nbsp &nbsp -  &nbsp &nbsp &nbsp Speciality  &nbsp &nbsp &nbsp   - Current Jobs">
                  <option><p class="text-left">Andrew - </p> <p class="text-center">Keyboards, Printers - </p> <p class="text-right">0</p> </option>
                  <option><p class="text-left">Bert  &nbsp &nbsp - </p> <p class="text-center">Displays, Keyboards - </p> <p class="text-right">1</p> </option>
                  <option><p class="text-left">Clara &nbsp - </p> <p class="text-center">General Software  &nbsp &nbsp  - </p> <p class="text-right">2</p> </option>
                  <option><p class="text-left">Mo &nbsp &nbsp &nbsp - </p> <p class="text-center">Networks  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp - </p> <p class="text-right">1</p> </option>
                </optgroup>
              </select> -->

			<br />
			<br />
			<div id = "submitButton" class="w3-padding-large w3-white" style="text-align:center">
			<a href = "../technician/main.php">
				<input class="w3-card w3-btn w3-row w3-light-grey" value="Submit/Save">
			</a>
			</div>

		</div>
	</div>
</div>

<div class="call_menu w3-center w3-padding w3-light-grey">
	Test!
</div>
<script>
$(document).ready(function() 
{
	$('#button').click(function(event) {
		$.ajax(
		{
			url: '/users',
			type: 'GET',
			dataType: "html",
			success: function(data)
			{
				var i = data.indexOf('<span id="cutoff"></span>');
				var result = data.substring(i);
				$('#userpage').html(result);
			}
		});
		return true;
	});

	var header_types = <?php echo json_encode($header_types); ?>;
	var types = <?php echo json_encode($types); ?>;

	header_types.forEach(function (header_type)
        {
            var group = $("<optgroup label='" + header_type.description + "' />");
            var o1 = new Option(header_type.description, header_type.id);
            $("#problem-select").append(group);
            $("#problem-select").append(o1);

            types.forEach(function (type)
	        {
	        	if (type.parent == header_type.id)
	        	{
		            var o2 = new Option(type.description, type.id);
		            $("#problem-select").append(o2);
		        }
	        });
        });

	
	
});

var solved = false;
$(document).ready(function () {
	if(solved) $('#solve').hide();
	$('#addCall').hide();
	$('#submitCallButton').hide();
    $('.problem-select').select2();
	$('.technician-select').select2();
    // New Problem Automatically Needs New Call
    showAddCall();
});
function solve()
{
	$('#solve').hide();
	var solved = true;
	var d = new Date($.now());
	$('#date').text();
	$('#status').text("Solved: " + d.getFullYear() +"-"+pad(d.getMonth())+"-"+pad(d.getDate())+ " " + pad(d.getHours()) + ":" + pad(d.getMinutes()) + " by 'Username'");
	$('#status').removeClass("w3-red");
	$('#status').addClass("w3-green");
}
function showAddCall()
{
	$('#addCall').show();
	$('#newCallButton').hide();
	$('#submitCallButton').show();
	var d = new Date($.now());
	$('#date').text(d.getFullYear() +"-"+pad(d.getMonth())+"-"+pad(d.getDate())+ " " + pad(d.getHours()) + ":" + pad(d.getMinutes()));
}
function pad(v)
{
	v=v.toString();
	if(v.length == 1) return "0" + ""+ v;
	else return v;
}
function submitCall()
{
	$('#newCallButton').show();
	$('#submitCallButton').hide();
	$('#addCall').before("<tr id='_3' class='w3-hover-light-grey solve'><td>" + $('#callerName').val()+ "</td><td>" + $('#date').text()+ "</td><td>" + $('#callerReason').val()+ "</td></tr>");
	$('#addCall').hide();
	$('#callerReason').val("");
	$('#callerName').val("");
}
</script>

@endsection