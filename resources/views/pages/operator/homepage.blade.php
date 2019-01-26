@extends('layouts.app')

@section('content')
<div class="">
        <div class="w3-container w3-white login w3-mobile" style='text-align:center'>
            <h3>New Call</h3><hr />
            <a class="blank" href="calls/create">
                <div class="bigbutton w3-card w3-button w3-row">
                    Log New Call
                </div>
            </a><br />
        </div>

        <div class="w3-container w3-white login w3-mobile" style='text-align:center'>
            <h3>Problems Without Specialists</h3><hr />
            <table id='reassign-table' class="display cell-border stripe hover" style="width:100%;">
                <thead>
                    <tr>
                        <th>Time Logged</th><th>Previous Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Importance</th><th>Hidden Column</th><th>---</th>
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
        </div>

        <div class="w3-container w3-white login w3-mobile" style='text-align:center'>
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
@endsection
