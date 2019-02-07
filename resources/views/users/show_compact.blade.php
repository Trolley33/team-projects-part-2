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
                        @if ($level == 1)
                        <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{$user->forename}} {{$user->surname}}<span class="icon">View</span></td>
                        @elseif ($level == 2)
                        <td>{{$user->forename}} {{$user->surname}}</td>
                        @endif
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Department Name</th>
                        @if ($level == 1)
                        <td class="editbutton" onclick="window.location.href = '/departments/{{$job_info->dID}}';">{{$job_info->name}}<span class="icon">View</span></td>
                        @elseif ($level == 2)
                         <td>{{$job_info->name}}</td>
                        @endif
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Job Title</th>
                        @if ($level == 1)
                        <td class="editbutton" onclick="window.location.href = '/jobs/{{$job_info->jID}}';">{{$job_info->title}}<span class="icon">View</span></td>
                        @elseif ($level == 2)
                         <td>{{$job_info->title}}</td>
                        @endif
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
                    @if ($job_info->access_level == '2')
                        <tr class="w3-hover-light-grey">
                            <th>Problem Specialism</th>
                        @if (!is_null($problem_type))
                            @if ($level == 1)
                             <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">
                                @if (!is_null($parent))
                                    ({{$parent->description}}) 
                                @endif
                                {{$problem_type->description}}<span class="icon">View</span></td>
                            </td>
                            @elseif ($level == 2)
                            <td>
                                @if (!is_null($parent))
                                    ({{$parent->description}}) 
                                @endif
                                {{$problem_type->description}}
                            </td>
                            @endif
                        @else
                            @if ($level == 1)
                             <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}/edit_specialism';" title="Edit">
                                Not Set<span class="icon">Edit</span>
                            </td>
                            @elseif ($level == 2)
                            <td>
                                Not Set
                            </td>
                            @endif
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}/edit_specialism';" title="Edit">
                                Not Set<span class="icon">Edit</span>
                            </td>
                            @endif
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Other Skills</th>
                            <td class="editbutton" onclick="window.location.href = '/skills/{{$user->id}}';">
                            Click to View
                            </td>
                        </tr>
                    @endif
                    @if (!is_null($timeoff))
                        <tr class="w3-text-deep-orange">
                            <th>Away From</th>
                            <td>{{$timeoff->startDate}}</td>
                        </tr>
                        <tr class="w3-text-deep-orange">
                            <th>Away Until</th>
                            <td>{{$timeoff->endDate}}</td>
                        </tr>
                        <tr class="w3-text-deep-orange">
                            <th>Reason for Time Off</th>
                            <td>{{$timeoff->reason}}</td>
                        </tr>
                    @endif
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