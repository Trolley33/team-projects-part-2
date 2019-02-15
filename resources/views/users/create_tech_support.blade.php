@extends('layouts.app')

@section('content')
<br />
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create New Technical Support Account</h2>
            <!-- Form to creat user with a Technical support Account -->
            {!! Form::open(['action' => 'UserController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Employee ID</th>
                        <td>{{Form::number('empID', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Employee ID'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Forename</th>
                        <td>{{Form::text('firstName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'First Name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Surname</th>
                        <td>{{Form::text('lastName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Last Name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Job Title</th>
                        <td><select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Phone Number</th>
                        <td>{{Form::tel('phone', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'e.g. 08001234567', 'pattern'=>'[0-9]{11}', 'oninvalid'=>"this.setCustomValidity('Please enter a valid phone UK number (11 consecutive numbers).')",
    'oninput'=>"this.setCustomValidity('')"])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Username</th>
                        <td>{{Form::text('username', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Username'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Password</th>
                        <td>{{Form::text('password', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Password', 'id'=>'password'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('isCaller', 'false')}}
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
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
        var selectedJob = <?php echo json_encode($job); ?>;
        //List of Job Titles for Technical Support
        jobs.forEach(function (job)
        {
            if (job.department_id == '1')
            {
                var o = new Option(job.title, job.id, false, job.id == selectedJob.id);
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
