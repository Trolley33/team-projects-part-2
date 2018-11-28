@extends('layouts.app')

<style>
    .call_menu{
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        margin-bottom: 100px;
    }

    .technician-select{
        width: 60%;
    }


    textarea{
        width: 100%;
    }

    table{
        width: 90%;
        margin-left: 5%;
    }

    td{
        padding: 10px;
    }

    th{
        padding: 10px;
        background-color: lightgrey;
    }
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white" style="text-align:center">
                <h2>Problem</h2>
                <table>
                    <tbody>
                        <tr id = "1" class="w3-hover-light-grey solve">
                            <th>Problem Number</th>
                            <td> #{{$problem->id}}</td>
                        </tr>
                        <tr id="2" class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td>
                                {{$problem->problem_type}}  
                            </td>
                        </tr>
                        <tr id = "3" class="w3-hover-light-grey solve">
                            <th>Hardware Serial#</th>
                            <td> #1230235 </td>
                        </tr>
                        <tr id = "4" class="w3-hover-light-grey solve">
                            <th>Operating System</th>
                            <td> N/A </td>
                        </tr>
                        <tr id = "5" class="w3-hover-light-grey solve">
                            <th>Software Affected</th>
                            <td> N/A </td>
                        </tr>
                        <tr id = "5" class="w3-hover-light-grey solve">
                            <th>Status</th>
                            <td id = "status" class="w3-red" > Unsolved </td>
                        </tr>
                    </tbody>
                </table>

                <br />

                <h2>Calls</h2>
                <table class = "w3-center">
                    <tbody>
                        <tr w3-light-grey>
                            <th> Caller Name </th>
                            <th> Time </th>
                            <th> Reason </th>
                        </tr>
                    </tbody>
                </table>

                <br />
                <h2>Assigned Technician</h2>
                {{$problem->assigned_to}}
                <br />
                <br />

                <div id = "submitButton" class="w3-padding-large w3-white" style="text-align:center">
                    <a href = "{{$problem->id}}/edit" class="blank">
                        <input class="w3-card w3-btn w3-row w3-light-grey" value="Edit Problem" />
                    </a>
                    <a href = "javascript:history.back()" class="blank">
                        <input class="w3-card w3-btn w3-row w3-light-grey" value="Go Back">
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
    var solved = false;

    $(document).ready(function () {
        if(solved) $('#solve').hide();
        $('#addCall').hide();
        $('#submitCallButton').hide();
        $('.problem-select').select2();
        $('.technician-select').select2();
    });

    function solve()
    {
        $('#solve').hide();
        var solved = true;
        var d = new Date($.now());
        $('#date').text();
        $('#status').text("Solved: " + d.getFullYear() +"-"+pad(d.getMonth())+"-"+pad(d.getDate())+ " " + pad(d.getHours()) + ":" + pad(d.getMinutes()) + " by 'Username'");
        $('#status').removeClass("w3-red");
        $('#status').addClass("w3-green");
    }

    function showAddCall()
    {
        $('#addCall').show();
        $('#newCallButton').hide();
        $('#submitCallButton').show();
        var d = new Date($.now());
        $('#date').text(d.getFullYear() +"-"+pad(d.getMonth())+"-"+pad(d.getDate())+ " " + pad(d.getHours()) + ":" + pad(d.getMinutes()));
    }

    function pad(v)
    {
        v=v.toString();
        if(v.length == 1) return "0" + ""+ v;
        else return v;
    }

    function submitCall()
    {
        $('#newCallButton').show();
        $('#submitCallButton').hide();
        $('#addCall').before("<tr id='_3' class='w3-hover-light-grey solve'><td>" + $('#callerName').val()+ "</td><td>" + $('#date').text()+ "</td><td>" + $('#callerReason').val()+ "</td></tr>");
        $('#addCall').hide();
        $('#callerReason').val("");
        $('#callerName').val("");

    }
    </script>
@endsection
