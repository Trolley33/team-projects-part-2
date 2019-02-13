@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@append_equipment', $problem->id], 'method' => 'GET', 'id'=>'addEquipmentForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    <table id='equipment-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Serial Number</th><th>Description</th><th>Model</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipment as $e)
                <?php $flag = true; ?>
                @foreach ($affected as $a)
                    @if ($e->id == $a->equipment_id)
                            <?php
                            $flag = false; 
                            break
                            ;?>
                    @endif
                @endforeach
                @if ($flag)
                <tr>
                    <td>{{$e->serial_number}}</td>
                    <td>{{$e->description}}</td><td>{{$e->model}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='equipment[]' value="{{$e->id}}" />
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>

        
    </table>
    <div style="text-align: center;">
        <input id="addEquipment" class="bigbutton w3-card w3-button w3-row" type="submit" value="Add Equipment to Problem" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function () 
{
    problem = <?php echo json_encode($problem) ?>;

    $('input:checkbox[name="equipment[]"]').change(
    function(){
        if ($('input:checkbox[name="equipment[]"]:checked').length > 0)
        {
            $('#addEquipment').prop('disabled', false);
        }
        else
        {
            $('#addEquipment').prop('disabled', true);
        }
    });

    $('.selectBox').click(function ()
    {
        var child = $(this).children('.selectChecked');
        child.prop('checked', !child.prop('checked'));
        
        if ($('input:checkbox[name="equipment[]"]:checked').length > 0)
        {
            $('#addEquipment').prop('disabled', false);
        }
        else
        {
            $('#addEquipment').prop('disabled', true);
        }
    });
    $('.selectChecked').click(function ()
    {
        $(this).prop('checked', !$(this).prop('checked'));
    });
    var table = $('#equipment-table').DataTable();
    $('#addEquipmentForm').submit(function (event) {
   		event.preventDefault();
   		var data = table.$('input, select').serialize() + "&problem-id="+problem.id;
       	window.location.href = "/problems/"+problem.id+'/equipment/add?'+data;
   	});	 
});
</script>

@endsection
