@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<!-- Name of user Specialism is being edited for -->
	<h2>Set Problem Specialism For: {{$user->forename}} {{$user->surname}}</h2>
	<form id="addSpecialismForm">
	<!-- List of problems types, where you can choose what problem type the Specialism is related to -->
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Problem Type ID</th><th>Problem Type Name</th><th>Select</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($problem_types as $pt)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d', $pt->id)}}</td>
				<td title="View" class="editbutton modalOpener" value='/problem_types/{{$pt->id}}/compact'>
					@if ($pt->parent_description != '0')
		            	({{$pt->parent_description}})
		        	@endif
                    {{$pt->description}}</td>
				<td title="Select" class="selectBox editbutton" style="text-align: center;">
					<input class="selectRadio" type="radio" name='ptype' value="{{$pt->id}}" />
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
	<div style="text-align: center;">
        <input id="addSpecialism" class="bigbutton w3-card w3-button w3-row" type="submit" value="Add Specialism to Specialist" style="width: 400px;" disabled/>
    </div>
    </form>
</div>
<div id="myModal" class="modal">
</div>
<script>

var modal;

$(document).ready( function ()
{
    var user = <?php echo json_encode($user); ?>;
    var pt = <?php echo ($pt_id ?? -1); ?>;
    var page = 0;
		//Enable button if a problem type is selected
    $('.selectBox').click(function ()
    {
    	$(this).children('.selectRadio').prop('checked', true);
    	$('#addSpecialism').prop('disabled', false);
    });
	//Enable button if a problem type is changed
	$('input:radio[name="ptype"]').change(
    	function(){
        $('#addSpecialism').prop('disabled', false);
    });

    $('input:radio[name="ptype"]').each(function (i, r)
    {
      var radio = $(r);
      if (radio.val() == pt)
      {
        page = i/10;
        radio.prop('checked', true);
        $('#addSpecialism').prop('disabled', false);
      }
    });
		//Form submit action
    $('#addSpecialismForm').submit(function ()
    {
        window.location.href = '/users/' + user.id + '/add_specialism/' + $("input[name='ptype']:checked").val();

        return false;
    })
    var table = $('#problem-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.page(Math.floor(page)).draw('page');
    // table.search(search).draw();
});

</script>

@endsection
