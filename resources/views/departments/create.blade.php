@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create a New Department</h2>
            <!-- Form to Create a new Department my submitting the Department name -->
            {!! Form::open(['action' => 'DepartmentController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('deptName', 'Department Name')}}</th>
                        <td>{{Form::text('deptName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Department Name'])}}</td>
                    </tr>

                </tbody>
            </table>
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}

            <br />
        </div>
    </div>
</div>
@endsection
