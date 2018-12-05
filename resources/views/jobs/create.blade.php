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
    });

</script>

@endsection
