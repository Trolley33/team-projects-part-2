@extends('layouts.app')

<style>
    .call_menu
    {
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        margin-bottom: 100px;
    }

    #info-table{
        width: 90%;
        margin-left: 5%;
    }

    #info-table td{
        padding: 10px;
    }

    #info-table th{
        padding: 10px;
        background-color: lightgrey;
    }
    .editbutton:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Call Viewer</h2>
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Related Problem ID</th>
                            <td class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}';">#{{$problem->id}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td>
                                {{$problem->problem_type}}  
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Description</th>
                            <td>
                                {{$problem->description}}  
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Caller ID</th>
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{sprintf('%04d', $user->employee_id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Caller Name</th>
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{$user->forename}} {{$user->surname}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Caller Phone Number</th>
                            <td>{{$user->phone_number}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td> {{$call->notes}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Logged At</th>
                            <td> {{$call->created_at}} </td>
                        </tr>
                    </tbody>
                </table>

                <br />
                <div style="text-align: center;">
                    <a class="blank" href="/calls/{{$call->id}}/edit">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Edit Call
                        </div>
                    </a><br />
                </div>
                    
                {!!Form::open(['action' => ['CallsController@destroy', $call->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete Call? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}
                
                {{Form::submit('Delete Account', ['class'=> "menu-item w3-card w3-button w3-row w3-red", 'style'=> 'width: 400px;'])}}
                
                {!!Form::close() !!}
                <br />
            </div>
        </div>
    </div>

    <script>

    function pad(v)
    {
        v=v.toString();
        if(v.length == 1) return "0" + ""+ v;
        else return v;
    }

    $(document).ready(function () {

    });
    </script>
@endsection
