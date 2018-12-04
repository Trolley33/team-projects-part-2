@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => 'JobController@store', 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />

                {{Form::label('department-select', 'Department')}}
                <br />

                <select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                </select>
                <br /><br />

                {{Form::label('jobTitle', 'Job Title')}}
                <br />
                {{Form::text('jobTitle', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Job Title'])}}
                <br />

                <div id="accessDiv">
                    <label for="accessLevel">Access Level</label>
                    <select id="accessLevel" name="accessLevel" class='w3-input w3-border w3-round'>
                    </select>
                </div>
                <br />
                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

<script>
    $(document).ready(function () 
    {
        $('#department-select').select2();

        var departments = <?php echo json_encode($departments) ;?>;

        var currentDept = <?php echo json_encode($dept);?>;

        departments.forEach(function (department)
        {
            var o = new Option(department.name, department.id, false, currentDept.id == department.id);
            $("#department-select").append(o);
        });

        if ($('#department-select :selected').val() == 1)
        {
            $('#accessLevel').empty();

            $('#accessLevel').append("<option value='0' disabled>Caller</option>");
            $('#accessLevel').append("<option value='1' selected>Operator</option>");
            $('#accessLevel').append("<option value='2'>Specialist</option>");
            $('#accessLevel').append("<option value='3'>Analyst</option>");
        }
        else
        {
            $('#accessLevel').empty();

            $('#accessLevel').append("<option value='0' selected>Caller</option>");
            $('#accessLevel').append("<option value='1' disabled>Operator</option>");
            $('#accessLevel').append("<option value='2' disabled>Specialist</option>");
            $('#accessLevel').append("<option value='3' disabled>Analyst</option>");
        }
        $('#accessLevel').select2();

        $('#department-select').change(function() 
        {
            if ($('#department-select :selected').val() == 1)
            {
                $('#accessLevel').empty();

                $('#accessLevel').append("<option value='0' disabled>Caller</option>");
                $('#accessLevel').append("<option value='1' selected>Operator</option>");
                $('#accessLevel').append("<option value='2'>Specialist</option>");
                $('#accessLevel').append("<option value='3'>Analyst</option>");
            }
            else
            {
                $('#accessLevel').empty();

                $('#accessLevel').append("<option value='0' selected>Caller</option>");
                $('#accessLevel').append("<option value='1' disabled>Operator</option>");
                $('#accessLevel').append("<option value='2' disabled>Specialist</option>");
                $('#accessLevel').append("<option value='3' disabled>Analyst</option>");
            }

            $('#accessLevel').select2();
        });
    });

</script>

@endsection
