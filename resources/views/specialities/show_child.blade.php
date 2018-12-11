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
                <h2>Problem Viewer</h2>
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Parent Problem ID</th>
                            <td class="editbutton">#{{sprintf('%04d', $parent->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Parent Problem Description</th>
                            <td class="editbutton">{{$parent->description}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type ID</th>
                            <td>#{{sprintf('%04d', $problem_type->id)}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
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
                                <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';">{{$specialist->forename}} {{$specialist->surname}}</td>
                                <td class="editbutton" style="text-align: center;">
                                    View/Edit
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr />
                <div style="text-align: center;">
                    <a class="blank" href="/specialities/{{$problem_type->id}}/edit">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Edit Problem Type
                        </div>
                    </a><br />
                </div>
                    
                {!!Form::open(['action' => ['SpecialityController@destroy', $problem_type->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete Problem Type? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}
                
                {{Form::submit('Delete Problem Type', ['class'=> "menu-item w3-card w3-button w3-row w3-red", 'style'=> 'width: 400px;'])}}
                
                {!!Form::close() !!}
                <br />
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
