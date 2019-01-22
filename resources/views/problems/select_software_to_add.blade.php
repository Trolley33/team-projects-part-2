@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@append_software', $problem->id], 'method' => 'POST', 'id'=>'addSoftwareForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    <table id='software-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Software ID</th><th>Name</th><th>Description</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($software as $s)
                <?php $flag = true; ?>
                @foreach ($affected as $a)
                    @if ($s->id == $a->software_id)
                            <?php
                            $flag = false; 
                            break
                            ;?>
                    @endif
                @endforeach
                @if ($flag)
                <tr>
                    <td>{{$s->id}}</td>
                    <td>{{$s->name}}</td><td>{{$s->description}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='software[]' value="{{$s->id}}" />
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>

        
    </table>
    <div style="text-align: center;">
        <input id="addSoftware" class="bigbutton w3-card w3-button w3-row" type="submit" value="Add Software to Problem" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function () 
{
    problem = <?php echo json_encode($problem) ?>;

    $('input:checkbox[name="software[]"]').change(
    function(){
        if ($('input:checkbox[name="software[]"]:checked').length > 0)
        {
            $('#addSoftware').prop('disabled', false);
        }
        else
        {
            $('#addSoftware').prop('disabled', true);
        }
    });

    $('.selectBox').click(function ()
    {
        var child = $(this).children('.selectChecked');
        child.prop('checked', !child.prop('checked'));

        if ($('input:checkbox[name="software[]"]:checked').length > 0)
        {
            $('#addSoftware').prop('disabled', false);
        }
        else
        {
            $('#addSoftware').prop('disabled', true);
        }
    });

    var table = $('#software-table').DataTable();

});
</script>

@endsection
