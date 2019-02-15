@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Edit {{$job->title}} Role</h2>
            {!! Form::open(['action' => ['JobController@update', $job->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <!-- Previous information -->
                    <tr class="w3-hover-light-grey">
                        <th>
                            {{Form::label('', 'Previous Department')}}</th>
                        <td><select id='prevDept' name='' class="w3-input" required  style="width: 100% !important;" disabled="true">
                            <option>{{$dept->name}}</option>
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>
                            {{Form::label('', 'Previous Title')}}</th>
                        <td>{{Form::text('', $job->title, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Previous Title', 'disabled'])}}</td>
                    </tr>
                    <!-- New information, the user will input -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('department-select', 'New Department')}}</th>
                        <td><select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('jobTitle', 'Job Title')}}</th>
                        <td>{{Form::text('jobTitle', $job->title, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'New Title'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#prevDept').select2();
        $('#department-select').select2();

        var departments = <?php echo json_encode($departments);?>;

        var currentDepartment = <?php echo json_encode($dept);?>

        // If the department ID is 1, that means it is the Technical Support Department, this is a special department with special job titles in this department can't be edited or deleted
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
