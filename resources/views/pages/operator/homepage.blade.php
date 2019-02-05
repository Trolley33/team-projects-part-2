@extends('layouts.app')

@section('content')
<div class="">
        <div class="call_menu w3-center w3-padding" style='text-align:center'>
            <a class="blank" href="calls/create">
                <div class="bigbutton w3-card w3-button w3-row">
                    <h3>Log New Call</h3>
                </div>
            </a><br />
        </div>

        <div  class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
            <h3>Problems Without Specialists</h3><hr />
            <table id='reassign-table' class="display cell-border stripe hover" style="width:100%;">
                <thead>
                    <tr>
                        <th>Time Logged</th><th>Previous Helper</th><th>Reason for Reassignment</th><th>Problem Type</th><th>Initial Caller</th><th>Importance</th><th>Hidden Column</th><th>---</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unassigned as $problem)
                    <tr>
                        <td>{{$problem->created_at}}</td>
                        <td class="editbutton modalOpener" value='/users/{{$problem->sID}}/compact'>
                        @if ($problem->sID != 0)
                            {{$problem->sForename}} {{$problem->sSurname}}
                        @else
                            None
                        @endif
                        </td>
                        <td>{{$problem->reason}}</td>
                        <td class="editbutton modalOpener" value='/problem_types/{{$problem->ptID}}/compact'>
                        @if ($problem->pDesc != '0') 
                            ({{$problem->pDesc}}) 
                        @endif 
                        {{$problem->ptDesc}}</td>
                        <td class="editbutton modalOpener" value='/users/{{$problem->uID}}/compact'>
                        {{$problem->forename}} {{$problem->surname}}</td>
                        <td class="{{$problem->class}}">{{$problem->text}}</td>
                        <td>{{$problem->level}}</td>
                        <td class="editbutton" onclick='window.location.href="/problems/{{$problem->pID}}"' style="text-align: center;">
                            Review
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="call_menu w3-center w3-padding" style='text-align:center'>
            <h3>Database Management Options</h3><hr />
            <a class="blank" href="/problems/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Problem Manager
                </div>
            </a><br />
            <a class="blank" href="/users/">
                <div class="bigbutton w3-card w3-button w3-row">
                    User Manager
                </div>
            </a><br />
            <a class="blank" href="/departments/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Department Manager
                </div>
            </a><br />
            <a class="blank" href="/jobs/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Job Manager
                </div>
            </a><br />
            <a class="blank" href="/equipment/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Equipment Manager
                </div>
            </a><br />
            <a class="blank" href="/software/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Software Manager
                </div>
            </a><br />
            <a class="blank" href="/problem_types/">
                <div class="bigbutton w3-card w3-button w3-row">
                    Problem Type Manager
                </div>
            </a><br />
          </div>
        <br />
    </div>

    <script>
    $(document).ready( function () 
    {
        var table = $('#reassign-table').dataTable({
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
    });
    </script>
@endsection
