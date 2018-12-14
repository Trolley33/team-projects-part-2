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

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Full Name</th>
                        <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{$user->forename}} {{$user->surname}}</td>
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
        </div>
    </div>
</div>

<script>

$(document).ready(function () {
});
</script>
@endsection