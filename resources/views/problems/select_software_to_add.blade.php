@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <!-- Problem ID for problem software needs to be added to -->
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@append_software', $problem->id], 'method' => 'GET', 'id'=>'addSoftwareForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    <!-- List of Software -->
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
                    <td style="text-align: right;">{{sprintf('%04d', $s->id)}}</td>
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
    //If one or more item of software is selected, enable the button to Add the software to the problem when checkbox is selected
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
    //If one or more item of equipment is selected, enable the button to Add the software to the problem when area around the select box is clicked on
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
    //When area around the select box is clicked on, select/deselect the box
    $('.selectChecked').click(function ()
    {
        $(this).prop('checked', !$(this).prop('checked'));
    });
    var table = $('#software-table').DataTable();
	$('#addSoftwareForm').submit(function (event) {
			event.preventDefault();
			var data = table.$('input, select').serialize() + "&problem-id="+problem.id;
			window.location.href = "/problems/"+problem.id+'/software/add?'+data;
	});
});
</script>

@endsection
