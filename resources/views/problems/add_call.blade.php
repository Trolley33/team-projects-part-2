@extends('layouts.app')

@section('content')

<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create New Call</h2>
            {!! Form::open(['action' => 'CallsController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <!-- Problem info (not editable) -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('', 'Problem ID')}}</th>
                        <td><sup><a href='/calls/create'>Edit Problem?</a></sup><br>{{Form::text('', sprintf('%04d', $problem->id), ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Problem ID', 'disabled'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('', 'Problem Description')}}</th>
                        <td>{{Form::text('', $problem->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Problem Description', 'disabled'])}}</td>
                    </tr>
                    <!-- Caller info (not editable) -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('', 'Caller ID')}}</th>
                        <td><sup><a href='/problems/{{$problem->id}}/add_call'>Edit Caller?</a></sup>{{Form::text('', sprintf('%04d', $user->employee_id), ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Caller ID', 'disabled'])}}</td>
                    </tr>       
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('caller-name', 'Caller Name')}}</th>
                        <td>{{Form::text('caller-name', $user->forename.' '.$user->surname, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Caller Name', 'disabled'])}}</td>
                    </tr>
                    {{Form::hidden('problem-id', $problem->id)}}
                    {{Form::hidden('user-id', $user->id)}}
                    <!-- Editable info -->
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('notes', 'Call Notes')}}</th>
                        <td>{{Form::textarea('notes', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Call Notes'])}}</td>
                    </tr>             
                </tbody>
            </table>
            {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    });
</script>

@endsection
