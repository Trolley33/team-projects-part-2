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

@extends('layouts.app')

@section('content')
<br />
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create New Caller Account</h2>
            {!! Form::open(['action' => 'UserController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Employee ID</th>
                        <td>{{Form::number('empID', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Employee ID'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Forename</th>
                        <td>{{Form::text('firstName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'First name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Surname</th>
                        <td>{{Form::text('lastName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Last Name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Department</th>
                        <td><select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Job Title</th>
                        <td><select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Phone Number</th>
                        <td>{{Form::number('phone', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Phone Number'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('isCaller', 'true')}}
            {{Form::submit('Submit', ['class'=> "menu-item w3-card w3-button w3-row w3-teal"])}}
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

        var selectedDept = <?php echo json_encode($dept); ?>;
        var selectedJob = <?php echo json_encode($job); ?>;

        departments.forEach(function (department)
        {
            var o = new Option(department.name, department.id, false, selectedDept.id == department.id);
            $("#department-select").append(o);
        });

        jobs.forEach(function (job)
        {
            if (job.department_id == $('#department-select :selected').val())
            {
                var o = new Option(job.title, job.id, false, selectedJob.id == job.id);
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