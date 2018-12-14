@extends('layouts.compact')


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
    .slideHeader:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Viewer</h2>
                <table id="info-table">
                    <tbody>
                        @if (!is_null($parent))
                            <tr class="w3-hover-light-grey solve">
                                <th>Parent Problem ID</th>
                                <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">#{{sprintf('%04d', $parent->id)}}</td>
                            </tr>
                            <tr class="w3-hover-light-grey solve">
                                <th>Parent Problem Description</th>
                                <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">{{$parent->description}}</td>
                            </tr>
                        @endif
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type ID</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$type->id}}';">#{{sprintf('%04d', $type->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$type->id}}';">
                                {{$type->description}}  
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
                                <th>Employee ID</th><th>Full Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specialists as $specialist)
                            <tr>
                                <td>{{sprintf('%04d', $specialist->employee_id)}}</td>
                                <td>{{$specialist->forename}} {{$specialist->surname}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
        $('#specialist-table').DataTable();

        $('.slideHeader').click(function(){
            $(this).next('.slideable').slideToggle();
        });
    });
    </script>
@endsection