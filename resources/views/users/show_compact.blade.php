@extends('layouts.compact')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Full Name</th>
                        <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{$user->forename}} {{$user->surname}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Department Name</th>
                        <td class="editbutton" onclick="window.location.href = '/departments/{{$job_info->dID}}';">{{$job_info->name}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Job Title</th>
                        <td class="editbutton" onclick="window.location.href = '/jobs/{{$job_info->jID}}';">{{$job_info->title}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Phone Number</th>
                        <td>{{$user->phone_number}}</td>
                    </tr>
                    @if ($job_info->access_level != 0)
                        <tr class="w3-hover-light-grey">
                            <th>Username</th>
                            <td>{{$user->username}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Password</th>
                            <td>{{$user->password}}</td>
                        </tr>
                    @endif
                    <tr class="w3-hover-light-grey">
                        <th>Account Creation Date</th>
                        <td>{{$user->created_at}}</td>
                    </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Specialism</th>
                            @if (!is_null($problem_type))
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">
                                @if (!is_null($parent))
                                    ({{$parent->description}}) 
                                @endif
                                {{$problem_type->description}}</td>
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