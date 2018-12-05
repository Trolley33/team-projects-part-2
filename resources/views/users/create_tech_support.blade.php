@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(array('action' => 'UserController@store', 'method' => 'POST', 'id' => 'techForm')) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                {{Form::label('empID', 'Employee ID')}}
                <br />
                {{Form::number('empID', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Employee ID'])}}
                <br />

                {{Form::label('firstName', 'First Name')}}
                <br />
                {{Form::text('firstName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'First Name'])}}
                <br />

                {{Form::label('lastName', 'Last Name')}}
                <br />
                {{Form::text('lastName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Last Name'])}}
                <br />

                {{Form::label('job-select', 'Job Role')}}

                <br />
                <select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                </select>
                <br /><br />
                {{Form::label('phone', 'Phone Number')}}
                <br />
                {{Form::number('phone', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Phone Number'])}}
                <br />

                {{Form::label('username', 'Username')}}
                <br />
                {{Form::text('username', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Username'])}}
                <br />

                {{Form::label('pass', 'Password')}}
                <br />
                {{Form::password('pass', ['required', 'class'=>'w3-input w3-border w3-round', 'id'=>'password', 'placeholder'=>'Password'])}}
                <br />

                {{Form::label('pass2', 'Password')}}
                <br />
                {{Form::password('pass2', ['required', 'class'=>'w3-input w3-border w3-round', 'id'=>'password2','placeholder'=>'Password'])}}
                <br />

                {{Form::label('visible', 'Show Password')}}
                {{Form::checkbox('visible', null, null, ['class'=>'w3-checkbox', 'id'=>'pass-visible'])}}
                <br />

                {{Form::hidden('isCaller', 'false')}}

                {{Form::submit('Submit', ['required', 'class'=>'w3-right w3-button w3-teal'])}}

        {!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('#job-select').select2();

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
        var jobs = <?php echo json_encode($jobs); ?>;
        var currentJob = <?php echo json_encode($job);?>;

        jobs.forEach(function (job)
        {
            var o = new Option(job.title, job.id, false, currentJob.id == job.id);
            $("#job-select").append(o);
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

    function submit()
    {
        alert("hello!");
    }
</script>

@endsection
