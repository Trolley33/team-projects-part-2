@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Select Specialist For Problem: {{sprintf('%04d', $problem->id)}}</h2>
  <hr />
  <h3><span class="editbutton modalOpener" value="/problem_types/{{$type->id}}/compact">
    @if (!is_null($parent)) 
      ({{$parent->description}}) 
    @endif{{$type->description}}</span></h3>
	<form id="addSpecialistForm">
	<!-- List of specialists -->
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Employee ID</th><th>Specialist Name</th><th>Problem Specialism</th><th>Other Skills</th><th>Hidden Column</th><th>No. of Active Jobs</th><th>Select</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($specialists as $s)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d', $s->employee_id)}}</td>
				<td class="editbutton modalOpener tooltip" value='/users/{{$s->id}}/compact'>
                    {{$s->forename}} {{$s->surname}}
                    @if (!is_null($s->startDate))
                        <!-- Check if time off within next week, only give warning (!) if so.-->
                        @if (time() + (60*60*24*7) >= strtotime($s->startDate))
                            <span class="w3-text-deep-orange editbutton modalOpener" value='/users/{{$s->id}}/compact'>(!)</span>
                            <span class="tooltiptext">Click for more info</span>
                        @endif
                    @endif
                </td>
        <td title="View" class="editbutton modalOpener" value='/problem_types/{{$s->pID}}/compact'>
            @if ($s->parent_description != '0')
                ({{$s->parent_description}})
            @endif
            {{$s->description}}</td>
        <td class="editbutton modalOpener visibleColumn tooltip" id='{{$s->id}}' value='/skills/{{$s->id}}/compact'>
            View
        </td>
        <!-- List of comma separated skills -->
        <td>
            {{$s->skill_list}}
        </td>
        <td style="text-align: right;">{{$s->jobs}}</td>
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

var skillCells;

$(document).ready( function ()
{
    var problem = <?php echo json_encode($problem); ?>;
    var parent = <?php echo json_encode($parent); ?>;
    var assigned = <?php echo json_encode($assigned); ?>;

    $('.selectBox').click(function ()
    {
        $(this).children('.selectRadio').prop('checked', true);
        $('#addSpecialist').prop('disabled', false);
    });

    $('input:radio[name="specialist"]').change(
        function(){
            $('#addSpecialist').prop('disabled', false);
    });

    $('input:radio[name="specialist"]').each(function (i, r)
    {
      var radio = $(r);
      if (assigned !== null)
      {
          if (radio.val() == assigned.id)
          {
            radio.prop('checked', true);
            $('#addSpecialist').prop('disabled', false);
          }
      }
    });

    $('#addSpecialistForm').submit(function ()
    {
        window.location.href = '/problems/' + problem.id + '/add_specialist/' + $("input[name='specialist']:checked").val();

        return false;
    });

    skillCells = $('.visibleColumn');

    var table = $('#problem-table').DataTable({
        order: [
          ['5', 'asc'],
          ['2', 'asc']
        ],
        "aoColumnDefs": [
            {
                "targets": [4],
                "visible": false
            },
        ]
    });

    $('.dataTables_filter label input').bind('input', function() {
        var text = $(this).val();
        skillCells.each(function (i) {
            var d = table.row(i).data();
            if (d[4].toLowerCase().includes(text.toLowerCase()) && text != '')
            {
                $(this).html("Match Found <span class='w3-text-green'>(?)</span><span class='tooltiptext'>Click for more info</span>");
                var dir = $(this).attr('value').split('/');
                $(this).attr('value', "/skills/"+dir[2]+"/compact?skill="+problem.problem_type);
            }
            else
            {
                $(this).html("View");
                return;
            }
        });
    });

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search');?>";

    if (search == '')
    {
        search = parent.description;
    }

    // Highlight cells with (?)
    skillCells.each(function (i) {
        var d = table.row(i).data();
        if (d[4].toLowerCase().includes(search.toLowerCase()) && search != '')
        {
            $(this).html("Match Found <span class='w3-text-green'>(?)</span><span class='tooltiptext'>Click for more info</span>");
            var dir = $(this).attr('value').split('/');
            $(this).attr('value', "/skills/"+dir[2]+"/compact?skill="+problem.problem_type);
        }
        else
        {
            $(this).html("View");
            return;
        }
    });

    table.search(search).draw();
});
</script>

@endsection
