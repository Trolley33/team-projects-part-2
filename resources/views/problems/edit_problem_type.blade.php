@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Edit Problem Type For Problem: <span class="editbutton" onclick="window.location.href='/problems/{{$problem->id}}'">{{sprintf('%04d', $problem->id)}}</span></h2>
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
				<td>{{sprintf('%04d', $pt->id)}}</td>
				<td title="View" class="editbutton modalOpener" value='/problem_types/{{$pt->id}}/compact'>
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

<script>
$(document).ready( function () 
{
    var problem = <?php echo json_encode($problem); ?>;
    var page = 0;
    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addProblemType').prop('disabled', false);
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
        page = i/10;
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
    table.page(Math.floor(page)).draw('page');
    table.search(search).draw();
});

</script>

@endsection
