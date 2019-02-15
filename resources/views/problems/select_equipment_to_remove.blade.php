@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <!-- Problem ID for problem, equipment needs to be removed from -->
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@delete_equipment', $problem->id], 'method' => 'GET', 'id'=>'removeEquipmentForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    {{Form::hidden('_method', 'DELETE')}}
    <!-- List of equipment assigned to the problem -->
    <table id='equipment-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Serial Number</th><th>Description</th><th>Model</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipment as $e)
                <tr>
                    <td>{{$e->serial_number}}</td>
                    <td>{{$e->description}}</td><td>{{$e->model}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='equipment[]' value="{{$e->id}}" />
                    </td>
                </tr>
            @endforeach
        </tbody>


    </table>
    <div style="text-align: center;">
        <input id="removeEquipment" class="bigbutton w3-card w3-button w3-row" type="submit" value="Remove Equipment from Problem" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function ()
{
    problem = <?php echo json_encode($problem) ?>;
    //If one or more item of equipment is selected, enable the button to Remove the equipment from the problem when the checkbox is selected
    $('input:checkbox[name="equipment[]"]').change(
    function(){
        if ($('input:checkbox[name="equipment[]"]:checked').length > 0)
        {
            $('#removeEquipment').prop('disabled', false);
        }
        else
        {
            $('#removeEquipment').prop('disabled', true);
        }
    });
    //If one or more item of equipment is selected, enable the button to Remove the equipment from the problem when area around the select box is clicked on
    $('.selectBox').click(function ()
    {
        var child = $(this).children('.selectChecked');
        child.prop('checked', !child.prop('checked'));

        if ($('input:checkbox[name="equipment[]"]:checked').length > 0)
        {
            $('#removeEquipment').prop('disabled', false);
        }
        else
        {
            $('#removeEquipment').prop('disabled', true);
        }
    });
    //When area around the select box is clicked on, select/deselect the box
    $('.selectChecked').click(function ()
    {
        $(this).prop('checked', !$(this).prop('checked'));
    });
    var table = $('#equipment-table').DataTable();
	$('#removeEquipmentForm').submit(function (event) {
		event.preventDefault();
		var data = table.$('input, select').serialize() + "&problem-id="+problem.id;
	   	window.location.href = "/problems/"+problem.id+'/equipment/remove?'+data;
	});
});
</script>

@endsection
