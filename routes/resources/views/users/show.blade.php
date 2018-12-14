@extends('layouts.app')

@section('content')
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

    .editbutton:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>

<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Full Name</th>
                        <td>{{$user->forename}} {{$user->surname}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Department Name</th>
                        <td class="editbutton" onclick="window.location.href = '/departments/{{$job_info->dID}}';">{{$job_info->name}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Job Title</th>
                        <td class="editbutton" onclick="window.location.href = '/jobs/{{$job_info->jID}}';">{{$job_info->title}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Phone Number</th>
                        <td>{{$user->phone_number}}</td>
                    </tr>
                    @if ($job_info->access_level != 0)
                        <tr class="w3-hover-light-grey solve">
                            <th>Username</th>
                            <td>{{$user->username}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Password</th>
                            <td>{{$user->password}}</td>
                        </tr>
                    @endif
                    <tr class="w3-hover-light-grey solve">
                        <th>Account Creation Date</th>
                        <td>{{$user->created_at}}</td>
                    </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Specialism</th>
                            @if (!is_null($problem_type))
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">{{$problem_type->description}}</td>
                            @else
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}/edit_specialism';" title="Edit">
                                Not Set
                            </td>
                            @endif
                        </tr>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a class="blank" href="/users/{{$user->id}}/edit">
                    <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                        Edit Information
                    </div>
                </a><br />
            </div>
            
            {!!Form::open(['action' => ['UserController@destroy', $user->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete User? This action cannot be undone, and may lead to unintended consequences.');"]) !!}

            {{Form::hidden('_method', 'DELETE')}}
            
            {{Form::submit('Delete Account', ['class'=> "menu-item w3-card w3-button w3-row w3-red", 'style'=> 'width: 400px;'])}}
            
            {!!Form::close() !!}

            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function() 
{
    $('#back-btn').click(function()
    {
        // window.location.replace('/users/');
    })
});
</script>
@endsection
