@extends('layouts.app')

@section('content')
<br />
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
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
                        <th>Department</th>
                        <td><select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
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
                </tbody>
            </table>
            {{Form::hidden('isCaller', 'true')}}
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Submit Changes', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
            <br />
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#department-select').select2();
        $('#job-select').select2();

        var departments = <?php echo json_encode($departments) ;?>;
        var jobs = <?php echo json_encode($jobs); ?>;

        var currentJob = 
        <?php 
        foreach($jobs as $job)
        {
            if ($job->id == $user->job_id)
            {
                echo json_encode($job);
                break;
            }
        }
        ?>;

        var currentDepartment = currentJob.department_id;

        departments.forEach(function (department)
        {
            var o = new Option(department.name, department.id, false, currentDepartment == department.id);
            $("#department-select").append(o);
        });

        jobs.forEach(function (job)
        {
            if (job.department_id == $('#department-select :selected').val())
            {
                var o = new Option(job.title, job.id, false, job.id == currentJob.id);
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