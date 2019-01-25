@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create New Job Title</h2>
            {!! Form::open(['action' => 'JobController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('department-select', 'Department')}}</th>
                        <td><select id='department-select' name='department-select' class="w3-input" required  style="width: 100% !important;">
                </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('jobTitle', 'Job Title')}}</th>
                        <td>{{Form::text('jobTitle', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Job Title'])}}</td>
                    </tr>                
                </tbody>
            </table>
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
