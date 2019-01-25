@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Viewer</h2>
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Parent Problem ID</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">{{sprintf('%04d', $parent->id)}}<span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Parent Problem<br/>Description</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">{{$parent->description}}<span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type ID</th>
                            <td>{{sprintf('%04d', $problem_type->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td>
                                {{$problem_type->description}}  
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- specialists -->
                <h2 class="slideHeader">Specialists</h2>
                <div class="slideable">
                    <table id='specialist-table' class="display cell-border stripe hover slidable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Employee ID</th><th>Full Name</th><th>---</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specialists as $specialist)
                            <tr>
                                <td>{{sprintf('%04d', $specialist->employee_id)}}</td>
                                <td>{{$specialist->forename}} {{$specialist->surname}}</td>
                                <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->specialist_id}}';" style="text-align: center;">
                                    View/Edit
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr />
                <div style="text-align: center;">
                    <a class="blank" href="/problem_types/{{$problem_type->id}}/edit">
                        <div class="bigbutton w3-card w3-button w3-row">
                            Edit Problem Type
                        </div>
                    </a><br />
                </div>
                    
                {!!Form::open(['action' => ['ProblemTypeController@destroy', $problem_type->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete Problem Type? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}
                
                {{Form::submit('Delete Problem Type', ['class'=> "bigbutton w3-card w3-button w3-row w3-red"])}}
                
                {!!Form::close() !!}
                <br />
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $('#specialist-table').DataTable();
    });
    </script>
@endsection
