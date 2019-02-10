@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Register New Equipment</h2>
            <!-- Form to Register a new item of Equipment my submitting the Serial Number, Equipment Description and Equipment Model -->
            {!! Form::open(['action' => 'EquipmentController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('serialNumber', 'Serial Number')}}</th>
                        <td>{{Form::text('serialNumber', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Serial Number'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('desc', 'Equipment Description')}}</th>
                        <td>{{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Equipment Description'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('model', 'Equipment Model')}}</th>
                        <td>{{Form::text('model', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Equipment Model'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
