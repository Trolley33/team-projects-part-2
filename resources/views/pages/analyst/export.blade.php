@extends('layouts.app')

@section('content')

<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<div id='errors'>
	</div>
    <h2>Select Tables to Export</h2>
		<!-- List of tables that the analyst can export -->
    {!! Form::open(['action' => ['ReviewController@export'], 'method' => 'POST', 'id'=>'addTableForm']) !!}
    <table id='table-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Table Name</th><th>Total Records</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $t => $count)
                <tr>
                    <td>{{$t}}</td>
                    <td>{{$count}}</td>
                    <td class="selectBox editbutton" style="text-align: center;">
                        <input class="selectChecked" type="checkbox" name='table[]' value="{{$t}}" />
                    </td>
                </tr>
            @endforeach
        </tbody>


    </table>
    <div style="text-align: center;">
        <input id="addTable" class="bigbutton w3-card w3-button w3-row" type="submit" value="Export Tables" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
$(document).ready( function ()
{
		//Enable Export Tables button if 1 or more table is selected
    $('input:checkbox[name="table[]"]').change(
    function(){
        if ($('input:checkbox[name="table[]"]:checked').length > 0)
        {
            $('#addTable').prop('disabled', false);
        }
        else
        {
            $('#addTable').prop('disabled', true);
        }
    });
		//Everytime a select box is checked or unchecked this function is run, Enable Export Tables button if 1 or more table is selected
    $('.selectBox').click(function ()
    {
        var child = $(this).children('.selectChecked');
        child.prop('checked', !child.prop('checked'));

        if ($('input:checkbox[name="table[]"]:checked').length > 0)
        {
            $('#addTable').prop('disabled', false);
        }
        else
        {
            $('#addTable').prop('disabled', true);
        }
    });

    $('.selectChecked').click(function ()
    {
        $(this).prop('checked', !$(this).prop('checked'));
    });

    var table = $('#table-table').DataTable({
        order: [[1, 'desc']]
    });
    // Redirect to URL to properly submit inputs from checkboxes.
    $('#addTableForm').submit(function (event) {
	var data = table.$('input, select').serialize();
	window.location = '/analyst/export/download?'+data;
	event.preventDefault();
    });

});
</script>

@endsection
