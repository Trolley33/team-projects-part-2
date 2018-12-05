@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => ['JobController@update', $job->id], 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <h2>{{$job->title}}</h2>
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                <!-- Previous information -->
                {{Form::label('', 'Previous Department')}}
                <br />

                <select id='prevDept' name='' class="w3-input" required  style="width: 100% !important;" disabled="true">
                    <option>{{$dept->name}}</option>
                </select>
                <br /><br />

                {{Form::label('', 'Previous Title')}}
                <br/>
                {{Form::text('', $job->title, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Previous Title', 'disabled'])}}
                <br />

                <!-- New information input -->
                {{Form::label('department-select', 'New Department')}}
                <br />

                <select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                </select>
                <br /><br />

                {{Form::label('jobTitle', 'New Title')}}
                <br/>
                {{Form::text('jobTitle', $job->title, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'New Title'])}}
                <br />

                {{Form::hidden('_method', 'PUT')}}

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

        

<script>
    $(document).ready(function () {
        $('#prevDept').select2();
        $('#department-select').select2();

        var departments = <?php echo json_encode($departments);?>;

        var currentDepartment = <?php echo json_encode($dept);?>

        departments.forEach(function (department)
        {
            var o = new Option(department.name, department.id, false, currentDepartment.id == department.id);
            if (department.id != 1)
            {
                $("#department-select").append(o);
            }
        });

    });
</script>

@endsection
