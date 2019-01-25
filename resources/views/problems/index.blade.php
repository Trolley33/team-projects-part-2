@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Ongoing Problems</h2>
    <table id='ongoing-table' class="display cell-border stripe hover" style="width:100%;">
        <thead>
            <tr>
                <th>Time Logged</th><th>Assigned Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Importance</th><th>Hidden Column</th><th>---</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ongoing as $problem)
            <tr>
                <td>{{$problem->created_at}}</td>
                <td class="editbutton modalOpener" value='/users/{{$problem->sID}}/compact'>
                @if ($problem->sID != 0)
                    {{$problem->sForename}} {{$problem->sSurname}}
                @else
                    None
                @endif
                </td>
                <td class="editbutton modalOpener" value='/problem_types/{{$problem->ptID}}/compact'>
                @if ($problem->pDesc != '0') 
                    ({{$problem->pDesc}}) 
                @endif 
                {{$problem->ptDesc}}</td>
                <td>{{$problem->description}}</td>
                <td class="editbutton modalOpener" value='/users/{{$problem->uID}}/compact'>
                {{$problem->forename}} {{$problem->surname}}</td>
                <td class="{{$problem->class}}">{{$problem->text}}</td>
                <td>{{$problem->level}}</td>
                <td class="editbutton" onclick='window.location.href="/problems/{{$problem->pID}}"' style="text-align: center;">
                    View/Edit
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div style="text-align: center;">
        <a class="blank" href="problems/create">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Problem
            </div>
        </a><br />
    </div>
    <hr />
    <h2>Resolved Problems</h2>
    <table id='resolved-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Time Logged</th><th>Solved At</th><th>Assigned Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>---</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resolved as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>{{$problem->solved_at}}</td>
                <td class="editbutton modalOpener" value='/users/{{$problem->sID}}/compact'>
                    @if ($problem->sID != 0)
                    {{$problem->sForename}} {{$problem->sSurname}}
                @else
                    None
                @endif
                </td>
                <td class="editbutton modalOpener" value='/problem_types/{{$problem->ptID}}/compact'>@if ($problem->pDesc != '0') ({{$problem->pDesc}}) @endif {{$problem->ptDesc}}</td>
                <td>{{$problem->description}}</td>
                <td class="editbutton modalOpener" value='/users/{{$problem->uID}}/compact'>{{$problem->forename}} {{$problem->surname}}</td>
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
        order: [[5, 'desc'], [0, 'desc']],
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
});
</script>

@endsection
