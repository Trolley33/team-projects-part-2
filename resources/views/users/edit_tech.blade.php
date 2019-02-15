@extends('layouts.app')

@section('content')
<br />
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <!-- Form to edit user information for Technical support Account -->
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            {!! Form::open(['action' => ['UserController@update', $user->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Employee ID</th>
                        <td>{{Form::number('empID', sprintf('%04d', $user->employee_id), ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->employee_id])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Forename</th>
                        <td>{{Form::text('firstName', $user->forename, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->forename])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Surname</th>
                        <td>{{Form::text('lastName', $user->surname, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->surname])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Job Title</th>
                        <td><select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Phone Number</th>
                        <td>{{Form::tel('phone', $user->phone_number, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->phone_number, 'pattern'=>'[0-9]{11}', 'oninvalid'=>"this.setCustomValidity('Please enter a valid phone UK number (11 consecutive numbers).')",
    'oninput'=>"this.setCustomValidity('')"])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Username</th>
                        <td>{{Form::text('username', $user->username, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->username])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Password</th>
                        <td>{{Form::text('password', $user->password, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>$user->password, 'id'=>'password'])}}</td>
                    </tr>
                    <!-- Information to be added if user is a Specialist -->
                    @if ($job->access_level == '2')
                        <tr class="w3-hover-light-grey">
                            <th>Problem Specialism</th>
                            @if (!is_null($problem_type))
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}/edit_specialism';" title="Edit">
                                @if (!is_null($parent))
                                    ({{$parent->description}})
                                @endif
                                {{$problem_type->description}}<span class="icon">Edit</span>
                            </td>
                            @else
                            <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}/edit_specialism';" title="Edit">
                                Not Set<span class="icon">Edit</span>
                            </td>
                            @endif
                        </tr>
                    @endif
                </tbody>
            </table>
            {{Form::hidden('isCaller', 'false')}}
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Submit Changes', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            <br />
            {{Form::label('visible', 'Show Password')}}
            {{Form::checkbox('visible', null, null, ['class'=>'w3-checkbox', 'id'=>'pass-visible'])}}

            {!! Form::close() !!}
            <br />
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#job-select').select2();

        var jobs = <?php echo json_encode($jobs); ?>;
        var currentJobID = <?php echo json_encode($user->job_id); ?>;
        //List of Job Titles for Technical Support
        jobs.forEach(function (job)
        {
            if (job.department_id == '1')
            {
                var o = new Option(job.title, job.id, false, job.id == currentJobID);
                $("#job-select").append(o);
            }
        });
        //Show hide password
        $('#password').attr('type', 'password');
        $('#pass-visible').change(function()
        {
            if (!this.checked)
            {
                $('#password').attr('type', 'password');
            }
            else
            {
                $('#password').attr('type', 'text');
            }
        });
    });
</script>

@endsection
