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

                {{Form::label('department-select', 'Department')}}
                <br />

                <select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                </select>
                <br /><br />

                {{Form::label('job-select', 'Job Title')}}
                <br />

                <select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                </select>

                <br /><br />

                {{Form::label('phone', 'Phone Number')}}
                <br />
                {{Form::number('phone', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Phone Number'])}}
                <br />

                {{Form::hidden('isCaller', 'true')}}

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>

        {!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('#department-select').select2();
        $('#job-select').select2();

        var departments = <?php echo json_encode($departments) ;?>;
        var jobs = <?php echo json_encode($jobs); ?>;

        var currentDept = <?php echo json_encode($dept);?>;

        var currentJob = <?php echo json_encode($job);?>;

        departments.forEach(function (department)
        {
            var o = new Option(department.name, department.id, false, currentDept.id == department.id);
            $("#department-select").append(o);
        });

        jobs.forEach(function (job)
        {
            if (job.department_id == $('#department-select :selected').val())
            {
                var o = new Option(job.title, job.id, false, currentJob.id == job.id);
                $("#job-select").append(o);
            }
        });

        $('#department-select').change(function() {
            $("#job-select").empty();
            jobs.forEach(function (job)
            {
               if (job.department_id == $('#department-select :selected').val())
                {
                    var o = new Option(job.title, job.id);
                    $("#job-select").append(o);
                }
            });
        });
    });
</script>

@endsection
