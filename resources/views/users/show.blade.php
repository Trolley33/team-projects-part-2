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
</style>

<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            <table>
                <tbody>
                    @if ($job_info->access_level != 0)
                        <tr id = "1" class="w3-hover-light-grey solve">
                            <th>Username</th>
                            <td>{{$user->username}}</td>
                        </tr>
                        <tr id = "2" class="w3-hover-light-grey solve">
                            <th>Password</th>
                            <td>{{$user->password}}</td>
                        </tr>
                    @endif
                    <tr id = "3" class="w3-hover-light-grey solve">
                        <th>Full Name</th>
                        <td>{{$user->forename}} {{$user->surname}}</td>
                    </tr>
                    <tr id = "4" class="w3-hover-light-grey solve">
                        <th>Department Name</th>
                        <td>{{$job_info->name}}</td>
                    </tr>
                    <tr id = "5" class="w3-hover-light-grey solve">
                        <th>Job Title</th>
                        <td>{{$job_info->title}}</td>
                    </tr>
                    <tr id = "6" class="w3-hover-light-grey solve">
                        <th>Phone Number</th>
                        <td>{{$user->phone_number}}</td>
                    </tr>
                    <tr id = "7" class="w3-hover-light-grey solve">
                        <th>Account Creation Date</th>
                        <td>{{$user->created_at}}</td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a class="blank" href="/users/{{$user->id}}/edit">
                    <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                        Edit Information
                    </div>
                </a><br />
                <a class="blank" href="/users/{{$user->id}}/delete">
                    <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                        Delete Account
                    </div>
                </a><br />
            </div>

            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function() 
{
    $('#back-btn').click(function()
    {
        window.location.replace('/users/');
    })
});
</script>
@endsection
