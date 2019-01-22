@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Select Specialist For Problem #{{sprintf('%04d', $problem->id)}}</h2>
  <hr />
  <h3>Problem Type: {{$type->description}}</h3>
	<form id="addSpecialistForm">
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Employee ID</th><th>Specialist Name</th><th>Problem Specialism</th><th>No. of Active Jobs</th><th>Select</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($specialists as $s)
			<tr>
				<td title="View" class="editbutton">{{sprintf('%04d', $s->employee_id)}}</td>
				<td title="View" class="editbutton modalOpener" value='/users/{{$s->id}}/compact'>{{$s->forename}} {{$s->surname}}</td>
        <td title="View" class="editbutton modalOpener" value='/problem_types/{{$s->pID}}/compact'>
            @if ($s->parent_description != '0')
                ({{$s->parent_description}})
            @endif
            {{$s->description}}</td>
        <td>{{$s->jobs}}</td>
				<td title="Select" class="selectBox editbutton" style="text-align: center;">
					<input class="selectRadio" type="radio" name='specialist' value="{{$s->id}}" />
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
    <div style="text-align: center;">
        <a class="blank" href="/problems/{{$problem->id}}/add_operator">
            <div class="bigbutton w3-card w3-button w3-row">
                Assign Problem to You
            </div>
        </a><br />
    </div>
    <div style="text-align: center;">
        <input id="addSpecialist" class="bigbutton w3-card w3-button w3-row" type="submit" value="Choose Specialist" disabled/>
    </div>

    </form>
</div>

<div id="myModal" class="modal">
</div>
<script>

var modal;

$(document).ready( function () 
{
    var problem = <?php echo json_encode($problem); ?>;
    var parent = <?php echo json_encode($parent); ?>;


    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addSpecialist').prop('disabled', false);
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

  $('input:radio[name="specialist"]').change(
      function(){
        $('#addSpecialist').prop('disabled', false);
    });

    $('input:radio[name="specialist"]').each(function (i, r)
    {
      var radio = $(r);
      if (radio.val() == problem.problem_type)
      {
        radio.prop('checked', true);
        $('#addSpecialist').prop('disabled', false);
      }
    });

    $('#addSpecialistForm').submit(function ()
    {
        window.location.href = '/problems/' + problem.id + '/add_specialist/' + $("input[name='specialist']:checked").val();

        return false;
    });
    var table = $('#problem-table').DataTable({
        order: [
          ['3', 'asc'],
          ['2', 'asc']
        ]
    });
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search');?>";

    if (search == '')
    {
        search = parent.description;
    }
    table.search(search).draw();
});

function closeModal ()
{
	modal.html('');
	modal.hide();
}
</script>

@endsection
