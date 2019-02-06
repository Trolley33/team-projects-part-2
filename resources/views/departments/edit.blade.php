@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Edit the {{$department->name}} Department</h2>
            <!-- Form to edit the  Department name -->
            {!! Form::open(['action' => ['DepartmentController@update', $department->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                  <!-- Pervious name of the Department that needs to be changed is taken from the previous page -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('', 'Previous Name')}}</th>
                        <td>{{Form::text('', $department->name, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Department Name', 'disabled'])}}</td>
                    </tr>
                    <!-- User enters new name for the department -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('deptName', 'New Name')}}</th>
                        <td>{{Form::text('deptName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Department Name'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
            <br />
        </div>
    </div>
</div>
@endsection
