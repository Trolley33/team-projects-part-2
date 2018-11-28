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
            <div class="w3-padding-large w3-white">
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
                            <th>Description</th>
                            <td> {{$problem->description}} </td>
                        </tr>
                        <tr id = "6" class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td> {{$problem->notes}} </td>
                        </tr>
                        <tr id = "7" class="w3-hover-light-grey solve">
                            <th>Status</th>
                            @if (count($resolved) === 1)

                                <td id = "status" class="w3-green" > Solved 
                                </td>
                            @else
                                <td id = "status" class="w3-red" > Unsolved </td>
                            @endif
                        </tr>
                        @if (count($resolved) === 1)

                            <tr id = "8" class="w3-hover-light-grey solve">
                                <th>Solution Notes</th>
                                <td>
                                @foreach ($resolved as $r)
                                    {{$r->solution_notes}}
                                @endforeach
                                </td>
                            </tr>
                        @endif
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

                        @foreach ($callers as $caller)
                        <tr>
                            <td>{{$caller->username}}</td>
                            <td>{{$caller->created_at}}</td>
                            <td>{{$caller->notes}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <br />
                <h2>Assigned Specialist</h2>
                    @foreach ($specialist as $s)
                        {{$s->username}}
                    @endforeach
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

    function pad(v)
    {
        v=v.toString();
        if(v.length == 1) return "0" + ""+ v;
        else return v;
    }
    </script>
@endsection
