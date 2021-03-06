@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <!-- Problem ID for problem, software needs to be removed from -->
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@delete_software', $problem->id], 'method' => 'GET', 'id'=>'removeSoftwareForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    {{Form::hidden('_method', 'DELETE')}}
    <!-- List of software assigned to the problem -->
    <table id='software-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Software ID</th><th>Name</th><th>Description</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($software as $s)
                <tr>
                    <td style="text-align: right;">{{sprintf('%04d', $s->id)}}</td>
                    <td>{{$s->name}}</td><td>{{$s->description}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='software[]' value="{{$s->id}}" />
                    </td>
                </tr>
            @endforeach
        </tbody>


    </table>
    <div style="text-align: center;">
        <input id="removeSoftware" class="bigbutton w3-card w3-button w3-row" type="submit" value="Remove Software from Problem" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function ()
{
    problem = <?php echo json_encode($problem) ?>;
    //If one or more item of software is selected, enable the button to Remove the software from the problem when the checkbox is selected
    $('input:checkbox[name="software[]"]').change(
    function(){
        if ($('input:checkbox[name="software[]"]:checked').length > 0)
        {
            $('#removeSoftware').prop('disabled', false);
        }
        else
        {
            $('#removeSoftware').prop('disabled', true);
        }
    });
    //If one or more item of software is selected, enable the button to Remove the software from the problem when area around the select box is clicked on
    $('.selectBox').click(function ()
    {
        var child = $(this).children('.selectChecked');
        child.prop('checked', !child.prop('checked'));

        if ($('input:checkbox[name="software[]"]:checked').length > 0)
        {
            $('#removeSoftware').prop('disabled', false);
        }
        else
        {
            $('#removeSoftware').prop('disabled', true);
        }
    });
    //When area around the select box is clicked on, select/deselect the box
    $('.selectChecked').click(function ()
    {
        $(this).prop('checked', !$(this).prop('checked'));
    });
    var table = $('#software-table').DataTable();
   	$('#removeSoftwareForm').submit(function (event) {
   		event.preventDefault();
   		var data = table.$('input, select').serialize() + "&problem-id="+problem.id;
      	window.location.href = "/problems/"+problem.id+'/software/remove?'+data;
   	});
});
</script>

@endsection
