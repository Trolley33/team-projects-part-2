@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
  <h2>Select Specialist For New Problem</h2>
  <h3>Creating New Problem for: <span class="editbutton modalOpener" value="/users/{{$user->id}}/compact">{{$user->forename}} {{$user->surname}}</span></h3>
  <h3>Problem Type: 
  <span class="editbutton modalOpener" value="/problem_types/{{$problem_type->id}}/compact">
    @if (!is_null($parent)) 
      ({{$parent->description}}) 
    @endif{{$problem_type->description}}</span></h3>
  {!! Form::open(['action' => 'ProblemController@store', 'method' => 'POST']) !!}
  <table id='specialist-table' class="display cell-border stripe hover" style="width:100%;">
    <thead>
      <tr>
        <th>Employee ID</th><th>Specialist Name</th><th>Problem Specialism</th><th>Other Skills</th><th>Hidden Column</th><th>No. of Active Jobs</th><th>Select</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($specialists as $s)
      <tr>
        <td style="text-align: right;">{{sprintf('%04d', $s->employee_id)}}</td>
        <td title="View" class="editbutton modalOpener" value='/users/{{$s->id}}/compact'>{{$s->forename}} {{$s->surname}}  
          @if (!is_null($s->startDate))
            <!-- Check if time off within next week, only give warning (!) if so.-->
            @if (time() + (60*60*24*7) >= strtotime($s->startDate))
                <span class="w3-text-deep-orange editbutton modalOpener" value='/users/{{$s->id}}/compact'>(!)</span>
            @endif
          @endif
        </td>
        <td title="View" class="editbutton modalOpener" value='/problem_types/{{$s->pID}}/compact'>
            @if ($s->parent_description != '0')
                ({{$s->parent_description}})
            @endif
            {{$s->description}}
        </td>
        <td title="" class="editbutton modalOpener visisbleColumn" id='{{$s->id}}' value='/skills/{{$s->id}}/compact'>
            View
        </td>
        <!-- Remove duplicate skills; print skills comma separated. -->
        <td>
            {{implode(',', array_unique(explode(',', $s->skills_list)))}}
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
    {{Form::hidden('desc', $problem_description)}}
    {{Form::hidden('notes', $problem_notes)}}
    {{Form::hidden('importance', $problem_importance)}}
    {{Form::hidden('user_id', $user->id)}}
    {{Form::hidden('problem_type_id', $problem_type->id)}}

    {{Form::submit('Assign Problem to You', ['class'=> "bigbutton w3-card w3-button w3-row", 'name'=>'submit'])}}
    <br />
    {{Form::submit('Assign Specialist', ['class'=> "bigbutton w3-card w3-button w3-row", 'id'=>'addSpecialist', 'name'=>'submit', 'disabled'])}}

    {!! Form::close() !!}
  </div>
    </form>
</div>
<script>
var skillCells;

$(document).ready( function () 
{

    var problem_type = <?php echo json_encode($problem_type); ?>;
    var parent = <?php 
        if (!is_null($parent))
        {
            echo json_encode($parent);
        }
        else
        {
            echo json_encode($problem_type);
        } 
        ?>;


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
      if (radio.val() == problem_type.id)
      {
        radio.prop('checked', true);
        $('#addSpecialist').prop('disabled', false);
      }
    });
    
   skillCells = $('.visisbleColumn');

    var table = $('#specialist-table').DataTable({
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
                $(this).html("Match Found <span class='w3-text-green'>(?)</span>");
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
            $(this).html("Match Found <span class='w3-text-green'>(?)</span>");
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
