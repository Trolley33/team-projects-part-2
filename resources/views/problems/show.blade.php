@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Viewer</h2>
                <!-- Main Information Table -->
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Created At</th>
                            <td>{{$problem->created_at}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Logged By</th>
                            <td class="editbutton modalOpener" value="/users/{{$operator->id}}/compact">{{$operator->forename}} {{$operator->surname}}<span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Number</th>
                            <td>{{sprintf('%04d', $problem->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">
                                @if (!is_null($parent))
                                    ({{$parent->description}})
                                @endif
                                {{$problem_type->description}}
                                <span class="icon">View</span>
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td> {{$problem->description}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Notes</th>
                            <td> {{$problem->notes}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Assigned Helper</th>
                            @if (!is_null($specialist))
                                @if ($specialist->id != 0)
                                <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';">
                                        {{$specialist->forename}} {{$specialist->surname}}
                                    <span class="icon">View</span></td>
                                </tr>
                                @else
                                <td class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}/edit_specialist';">
                                    None<span class="icon">Edit</span>
                                </td>
                                @endif
                            @else
                                <td class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}/edit_specialist';">
                                        None<span class="icon">Edit</span>
                                    </td>
                            @endif
                        @if (is_null($resolved))
                        <tr class="w3-hover-light-grey">
                            <th>Importance</th>
                            <td class="{{$importance->class}}">{{$importance->text}}</td>
                        </tr>
                        @endif
                        <tr class="w3-hover-light-grey">
                            <th>Status</th>
                            @if (!is_null($resolved))

                                <td class="w3-green"> Solved ({{$resolved->created_at}})
                                </td>
                            @else
                                <td class="w3-red editbutton modalOpener"value='/problems/{{$problem->id}}/solve/compact'> Unsolved</td>
                            @endif
                        </tr>
                        @if (!is_null($resolved))

                            <tr id = "8" class="w3-hover-light-grey">
                                <th>Solution Notes</th>
                                <td>
                                    {{$resolved->solution_notes}}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- Buttons -->
                <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/edit">
                        <div class="w3-card w3-button w3-row bigbutton">
                            Edit Details
                        </div>
                    </a><br />
                    {!!Form::open(['action' => ['ProblemController@destroy', $problem->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete Problem? This action cannot be undone, and may lead to unintended consequences.');"]) !!}

                    {{Form::hidden('_method', 'DELETE')}}
                    
                    {{Form::submit('Delete Problem', ['class'=> "bigbutton w3-card w3-button w3-row w3-red"])}}
                    
                    {!!Form::close() !!}
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
                                <td class="editbutton modalOpener" value='/users/{{$caller->id}}/compact';">{{$caller->forename}} {{$caller->surname}}</td>
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
                        <div class="bigbutton w3-card w3-button w3-row">
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
                        <div class="bigbutton w3-card w3-button w3-row">
                            Add New Affected Equipment
                        </div>
                    </a><br />
                    <a class="blank" href="/problems/{{$problem->id}}/remove_equipment">
                        <div class="bigbutton w3-card w3-button w3-row">
                            Remove Affected Equipment
                        </div>
                    </a><br />
                    </div>
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
                        <div class="bigbutton w3-card w3-button w3-row">
                            Add New Affected Software
                        </div>
                    </a><br />
                    <a class="blank" href="/problems/{{$problem->id}}/remove_software">
                        <div class="bigbutton w3-card w3-button w3-row">
                            Remove Affected Software
                        </div>
                    </a><br />
                    </div>
                </div>
                <hr />
                <!-- reassignments -->
                @if (!is_null($reassignments))
                    <h2 class="slideHeader">Previously Assigned Helpers</h2>
                    <div class="slideable">
                        <table id='reassign-table' class="display cell-border stripe hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Requested At</th>
                                    <th>Previous Helper</th><th>Reason for Reassigning</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reassignments as $r)
                                <tr>
                                    <td>{{$r->created_at}}</td>
                                    <td class="editbutton modalOpener" value='/users/{{$r->uID}}/compact'>{{$r->forename}} {{$r->surname}}</td>
                                    <td>{{$r->reason}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr />
                @endif
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        var table = $('#caller-table').dataTable({
            order: [[2, 'asc']]
        });

        var eTable = $('#equipment-table').DataTable();
        var sTable = $('#software-table').DataTable();
        var rTable = $('#reassign-table').DataTable({
                order: [[0, 'asc']]
        });
    });
    </script>
@endsection
