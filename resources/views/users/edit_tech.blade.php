@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => ['UserController@update', $user->id], 'method' => 'POST', 'id' => 'techForm']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                {{Form::label('empID', 'Employee ID')}}
                <br />
                {{Form::number('empID', $user->employee_id, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Employee ID'])}}
                <br />

                {{Form::label('firstName', 'First Name')}}
                <br />
                {{Form::text('firstName', $user->forename, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'First Name'])}}
                <br />

                {{Form::label('lastName', 'Last Name')}}
                <br />
                {{Form::text('lastName', $user->surname, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Last Name'])}}
                <br />

                {{Form::label('job-select', 'Job Role')}}

                <br />
                <select id='job-select' name='job-select' class="w3-input" required style="width: 100% !important;">
                    @foreach($jobs as $job)
                        @if ($job->id == $user->job_id)
                        <option value='{{$job->id}}' selected="true">
                            {{$job->title}}
                        </option>
                        @else
                        <option value='{{$job->id}}'>
                            {{$job->title}}
                        </option>
                        @endif
                    @endforeach
                </select>
                <br /><br />
                {{Form::label('phone', 'Phone Number')}}
                <br />
                {{Form::number('phone', $user->phone_number, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Phone Number'])}}
                <br />

                {{Form::label('username', 'Username')}}
                <br />
                {{Form::text('username', $user->username, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Username'])}}
                <br />

                {{Form::label('pass', 'Password')}}
                <br />
                {{Form::password('pass', ['required', 'id'=>'password', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Password'])}}
                <br />

                {{Form::label('pass2', 'Confirm Password')}}
                <br />
                {{Form::password('pass2', ['required', 'id'=>'password2', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Confirm Password'])}}
                <br />
                {{Form::label('visible', 'Show Password')}}
                {{Form::checkbox('visible', null, null, ['class'=>'w3-checkbox', 'id'=>'pass-visible'])}}
                <br />

                {{Form::hidden('isCaller', 'false')}}
                {{Form::hidden('_method', 'PUT')}}

                {{Form::submit('Submit', ['required', 'class'=>'w3-right w3-button w3-teal'])}}

            </div>

        {!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('#job-select').select2();

        var pass = '<?php echo $user->password ?>';
        $('#password').val(pass);
        $('#password2').val(pass);
        $('#pass-visible').change(function()
        {
            if (!this.checked)
            {
                $('#password').attr('type', 'password');
                $('#password2').attr('type', 'password');
            }
            else
            {
                $('#password').attr('type', 'text');
                $('#password2').attr('type', 'text'); 
            }
        });

        
        $('#techForm').submit(function ()
        {
            // Verify form content.
            var flag = true;
            $('#messages').html("");
            if ($('#password').val() != $('#password2').val())
            {
                $('#messages').append("<div class='w3-red' style='width: 100%;'>Passwords do not match.</div>");
                flag = false;
            }

            return flag;
        });
    });
</script>

@endsection
