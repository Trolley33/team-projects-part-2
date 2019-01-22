@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Select Problem Type For Problem #{{sprintf('%04d', $problem->id)}}</h2>
	<form id="addProblemTypeForm">
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Problem Type ID</th><th>Problem Type Name</th><th>Select</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($problem_types as $pt)
			<tr>
				<td title="View" class="editbutton">{{sprintf('%04d', $pt->id)}}</td>
				<td title="View" class="editbutton modalOpener" value='{{$pt->id}}'>
          @if ($pt->parent_description != '0')
            ({{$pt->parent_description}})
          @endif
          {{$pt->description}}
        </td>
				<td title="Select" class="selectBox editbutton" style="text-align: center;">
					<input class="selectRadio" type="radio" name='ptype' value="{{$pt->id}}" />
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<div style="text-align: center;">
        <input id="addProblemType" class="bigbutton w3-card w3-button w3-row" type="submit" value="Choose Problem Type" disabled/>
    </div>
    </form>
</div>

<div id="myModal" class="modal" value=''>
</div>

<script>

var modal;

$(document).ready( function () 
{
    var problem = <?php echo json_encode($problem); ?>;


    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addProblemType').prop('disabled', false);
    });


  modal = $('#myModal');

  $(".modalOpener").click(function() {
    $.get(
        $(this).attr('value'),
        function (data) {
            modal.html(data);
            $('#myModal div').first().prepend('<span onclick="closeModal()" class="close">&times;</span>')
        }
    );

      modal.show();
  });

  $(window).click(function(event) {
    var target = $(event.target);

    if (!target.hasClass('modalOpener'))
    { 
      if (target.closest('.modal div').length == 0)
      {
        closeModal();
      }
    }
  });

  $('input:radio[name="ptype"]').change(
      function(){
        $('#addProblemType').prop('disabled', false);
    });

    $('input:radio[name="ptype"]').each(function (i, r)
    {
      var radio = $(r);
      if (radio.val() == problem.problem_type)
      {
        radio.prop('checked', true);
        $('#addProblemType').prop('disabled', false);
      }
    });

    $('#addProblemTypeForm').submit(function ()
    {
        window.location.href = '/problems/' + problem.id + '/add_problem_type/' + $("input[name='ptype']:checked").val();

        return false;
    });
    
    var table = $('#problem-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});

function closeModal ()
{
	modal.html('');
	modal.hide();
}
</script>

@endsection
