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
    <form id="addCallForm">
    <table id='problem-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Time Logged</th><th>Problem ID</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Importance</th><th>Hidden Column</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ongoing as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>{{sprintf('%04d',$problem->pID)}}</td><td>@if ($problem->pDesc != '0') ({{$problem->pDesc}})@endif{{$problem->problemType}}</td><td>{{$problem->description}}</td><td>{{$problem->forename}} {{$problem->surname}}</td>
                <td class="{{$problem->class}}">{{$problem->text}}</td><td>{{$problem->level}}</td>
                <td class="selectBox editbutton" style="text-align: center;">
                    <input class="selectRadio" type="radio" name='existing' value="{{$problem->pID}}" />
                </td>
            </tr>
            @endforeach
        </tbody>

        
    </table>
    <div style="text-align: center;">
        <input id="addCall" class="menu-item w3-card w3-button w3-row" type="submit" value="Add Call to Problem" style="width: 400px;" disabled/>
    </div>
    </form>

    <div style="text-align: center;">
        <a class="blank" href="/problems/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Problem
            </div>
        </a><br />
    </div>
</div>


<script>
$(document).ready( function () 
{
    var table = $('#problem-table').dataTable({
        order: [
          ['5', 'desc']
        ],
        "aoColumnDefs": [
            {
                "iDataSort": 6,
                "aTargets": [5] 
            },
            {
                "targets": [6],
                "visible": false,
                "searchable": false
            },
        ]
      });
    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addCall').prop('disabled', false);
    });

    $('input:radio[name="existing"]').change(
    function(){
        $('#addCall').prop('disabled', false);
    });

    $('#addCallForm').submit(function ()
    {
        window.location.href = '/problems/' + $("input[name='existing']:checked").val() + '/add_call';
        return false;
    })

});
</script>

@endsection
