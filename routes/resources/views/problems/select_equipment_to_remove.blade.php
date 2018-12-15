@extends('layouts.app')

<style>
.editbutton:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}
</style>

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@delete_equipment', $problem->id], 'method' => 'POST', 'id'=>'removeEquipmentForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    {{Form::hidden('_method', 'DELETE')}}
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
        <input id="removeEquipment" class="menu-item w3-card w3-button w3-row" type="submit" value="Remove Equipment from Problem" style="width: 400px;" disabled/>
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
            $('#removeEquipment').prop('disabled', false);
        }
        else
        {
            $('#removeEquipment').prop('disabled', true);
        }
    });

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
    var table = $('#equipment-table').DataTable();

});
</script>

@endsection