@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => 'UserController@store', 'method' => 'POST']) !!}
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
                    @foreach($jobs as $job)
                        <option value='{{$job->id}}'>
                            {{$job->title}}
                        </option>
                    @endforeach
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

                {{Form::label('password', 'Password')}}
                <br />
                {{Form::password('password', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Password'])}}
                <br />

                {{Form::label('password2', 'Password')}}
                <br />
                {{Form::password('password2', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Password'])}}
                <br />

                {{Form::hidden('isCaller', 'false')}}

                {{Form::submit('Submit', ['required', 'class'=>'w3-right w3-button w3-teal'])}}

                <!--
                <label>First Name</label> <br />
                <input class="w3-input w3-border w3-round" type="text" name="forename" placeholder="First Name" required/><br />
                <label>Last Name</label> <br />
                <input class="w3-input w3-border w3-round" type="text" name="surname" placeholder="Last Name" required/><br />

                <label>Job Title</label> <br />
                <input class="w3-input w3-border w3-round" type="text" name="job-title" placeholder="Job Title"/><br />

                <label>Department</label> <br />
                <input class="w3-input w3-border w3-round" type="text" name="department" placeholder="Department"/><br />

                <label>Select Role</label> <br />
                <select class='role w3-input'required>
                      <option>Helpesk Operator</option>
                      <option>Helpesk Specialist</option>
                      <option>Helpesk Analyst</option>
                </select> <br /><br />
                <label>Username</label> <br />
                <input class="w3-input w3-border w3-round" type="text" name="username" placeholder="Username" required/><br />
                <label>Choose Password</label> <br />
                <input class="w3-input w3-border w3-round" type="password" name="password" placeholder="Password" required/><br />
                <label>Confirm Password</label> <br />
                <input class="w3-input w3-border w3-round" type="password" name="confirm-password" placeholder="Password" required/><br />
                <input class="w3-right w3-button w3-teal" type="submit" name="submit" value="Register"/><br /><br />
                -->
            </div>

        {!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('#job-select').select2();
    });
</script>

@endsection
