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
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Viewer</h2>
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Number</th>
                            <td> #{{sprintf('%04d', $problem->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">
                                {{$problem_type->description}}
                                <span class="icon">View</span>  
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td> {{$problem->description}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td> {{$problem->notes}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Assigned Helper</th>
                            <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';">{{$specialist->forename}} {{$specialist->surname}}
                                <span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Status</th>
                            @if (!is_null($resolved))

                                <td class="w3-green"> Solved 
                                </td>
                            @else
                                <td class="w3-red"> Unsolved </td>
                            @endif
                        </tr>
                        @if (!is_null($resolved))

                            <tr id = "8" class="w3-hover-light-grey solve">
                                <th>Solution Notes</th>
                                <td>
                                    {{$resolved->solution_notes}}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/edit">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Edit Details
                        </div>
                    </a><br />
                </div>
                <hr />
                <!-- callers -->
                <h2 class="slideHeader">Calls</h2>
                <div class="slideable">
                    <table id='caller-table' class="display cell-border stripe hover slidable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Full Name</th><th>Notes</th><th>Logged At</th><th>---</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($callers as $caller)
                            <tr>
                                <td class="editbutton" onclick="window.location.href = '/users/{{$caller->id}}';">{{$caller->forename}} {{$caller->surname}}</td>
                                <td>{{$caller->notes}}</td>
                                <td>{{$caller->cAT}}</td>
                                <td class="editbutton" onclick="window.location.href = '/calls/{{$caller->cID}}';" style="text-align: center;">
                                    View/Edit
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/add_call">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Add New Call
                        </div>
                    </a><br />
                    </div>
                </div>
                <hr />
                <!-- hardware -->
                <h2 class="slideHeader">Affected Equipment</h2>
                <div class="slideable">
                    <table id='equipment-table' class="display cell-border stripe hover slidable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Serial Number</th><th>Description</th><th>Model</th><th>---</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hardware as $h)
                            <tr>
                                <td>{{$h->serial_number}}</td>
                                <td>{{$h->description}}</td>
                                <td>{{$h->model}}</td>
                                <td class="editbutton" onclick="window.location.href = '/equipment/{{$h->id}}';" style="text-align: center;">
                                    View/Edit
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/add_equipment">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Add New Affected Equipment
                        </div>
                    </a><br />
                    </div>
                    <a class="blank" href="/problems/{{$problem->id}}/remove_equipment">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Remove Affected Equipment
                        </div>
                    </a><br />
                </div>
                <hr />
                <!-- software -->
                <h2 class="slideHeader">Affected Software</h2>
                <div class="slideable">
                    <table id='software-table' class="display cell-border stripe hover" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Software Name</th><th>Description</th><th>---</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($software as $s)
                            <tr>
                                <td>{{$s->name}}</td>
                                <td>{{$s->description}}</td>
                                <td class="editbutton" onclick="window.location.href = '/software/{{$s->id}}';" style="text-align: center;">
                                    View/Edit
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/add_software">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Add New Affected Software
                        </div>
                    </a><br />
                    <a class="blank" href="/problems/{{$problem->id}}/remove_software">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Remove Affected Software
                        </div>
                    </a><br />
                    </div>
                </div>
                <hr />

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

        var table = $('#caller-table').dataTable({
            order: [[2, 'asc']]
        });

        var table2 = $('#equipment-table').DataTable();
        var table3 = $('#software-table').DataTable();
        
        $('.slideHeader').click(function(){
            $(this).next('.slideable').slideToggle();
        });

    });
    </script>
@endsection
