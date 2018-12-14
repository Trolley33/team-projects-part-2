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
    {!! Form::open(['action' => ['ProblemController@delete_software', $problem->id], 'method' => 'POST', 'id'=>'removeSoftwareForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    {{Form::hidden('_method', 'DELETE')}}
    <table id='software-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Software ID</th><th>Name</th><th>Description</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($software as $s)
                <tr>
                    <td>{{$s->id}}</td>
                    <td>{{$s->name}}</td><td>{{$s->description}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='software[]' value="{{$s->id}}" />
                    </td>
                </tr>
            @endforeach
        </tbody>

        
    </table>
    <div style="text-align: center;">
        <input id="removeSoftware" class="menu-item w3-card w3-button w3-row" type="submit" value="Remove Software from Problem" style="width: 400px;" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function () 
{
    problem = <?php echo json_encode($problem) ?>;
    var table = $('#software-table').DataTable();

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
});
</script>

@endsection
