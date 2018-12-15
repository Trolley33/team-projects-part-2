@extends('layouts.app')

<style>
.editbutton:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}
</style>


@section('content')
<?php
    $importance = array(
        array('Low', 'w3-green'),
        array('Medium', 'w3-deep-orange'),
        array('High', 'w3-red'),
        array('Urgent', 'w3-purple')
    );
?>
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Ongoing Problems</h2>
    <table id='ongoing-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Time Logged</th><th>Assigned Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Importance</th><th>Hidden Column</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ongoing as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>@if ($problem->sID != 0)
                    {{$problem->sForename}} {{$problem->sSurname}}
                @else
                    None
                @endif
                </td><td>@if ($problem->pDesc != '0') ({{$problem->pDesc}}) @endif {{$problem->ptDesc}}</td><td>{{$problem->description}}</td><td>{{$problem->forename}} {{$problem->surname}}</td><td class="{{$importance[$problem->importance][1]}}">{{$importance[$problem->importance][0]}}</td><td>{{$problem->importance}}</td>
                <td class="editbutton" value='{{$problem->pID}}' style="text-align: center;">
                    View/Edit
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div style="text-align: center;">
        <a class="blank" href="problems/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Problem
            </div>
        </a><br />
    </div>
    <hr />
    <h2>Resolved Problems</h2>
    <table id='resolved-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Time Logged</th><th>Assigned Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Solved At</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resolved as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>@if ($problem->sID != 0)
                    {{$problem->sForename}} {{$problem->sSurname}}
                @else
                    None
                @endif
                </td><td>@if ($problem->pDesc != '0') ({{$problem->pDesc}}) @endif {{$problem->ptDesc}}</td><td>{{$problem->description}}</td><td>{{$problem->forename}} {{$problem->surname}}</td><td>some time</td>
                <td class="editbutton" value='{{$problem->pID}}' style="text-align: center;">
                    View/Edit
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script>
$(document).ready( function () 
{
    var table = $('#ongoing-table').dataTable({
        order: [[4, 'desc'], [0, 'desc']],
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

    var table = $('#resolved-table').dataTable({
        order: [[5, 'desc']]
    });

    $('.editbutton').click(function() 
    {
        window.location.href = '/problems/' + $(this).attr('value');
    });
});
</script>

@endsection
