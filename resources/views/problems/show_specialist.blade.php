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
                            <th>Problem Number</th>
                            <td>{{sprintf('%04d', $problem->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type</th>
                            <td class="editbutton modalOpener" value="/problem_types/{{$problem_type->id}}/compact">
                                @if (!is_null($parent))
                                    ({{$parent->description}})
                                @endif
                                {{$problem_type->description}}<span class="icon">View</span>
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
                            <td>
                                {{$specialist->forename}} {{$specialist->surname}}</td>
                            </tr>
                            @else
                                <td>
                                    None
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
                                <td class="w3-red"> Unsolved </td>
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
                        <div class="bigbutton w3-card w3-button w3-row">
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
                                <th>Full Name</th><th>Notes</th><th>Logged At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($callers as $caller)
                            <tr>
                                <td class="editbutton modalOpener" value="/users/{{$caller->id}}/compact">{{$caller->forename}} {{$caller->surname}}</td>
                                <td>{{$caller->notes}}</td>
                                <td>{{$caller->cAT}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr />
                <!-- hardware -->
                <h2 class="slideHeader">Affected Equipment</h2>
                <div class="slideable">
                    <table id='equipment-table' class="display cell-border stripe hover slidable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Serial Number</th><th>Description</th><th>Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hardware as $h)
                            <tr>
                                <td>{{$h->serial_number}}</td>
                                <td>{{$h->description}}</td>
                                <td>{{$h->model}}</td>
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
                                <th>Software Name</th><th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($software as $s)
                            <tr>
                                <td>{{$s->name}}</td>
                                <td>{{$s->description}}</td>
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

            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {

        var table = $('#caller-table').dataTable({
            order: [[2, 'asc']]
        });

        var table2 = $('#equipment-table').DataTable();
        var table3 = $('#software-table').DataTable();

    });
    </script>
@endsection
