@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => 'CallsController@store', 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                <!-- Problem info (not editable) -->
                <sup><a href='/calls/create'>Edit Problem?</a></sup>
                <br />
                {{Form::label('', 'Problem ID')}}
                <br />
                {{Form::text('', sprintf('%04d', $problem->id), ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Problem ID', 'disabled'])}}
                <br />

                {{Form::label('', 'Problem Description')}}
                <br />
                {{Form::text('', $problem->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Problem Description', 'disabled'])}}
                <hr />
                <!-- Caller info (not editable) -->
                <sup><a href='/problems/{{$problem->id}}/add_call'>Edit Caller?</a></sup>
                <br />
                {{Form::label('', 'Caller ID')}}
                <br />
                {{Form::text('', sprintf('%04d', $user->employee_id), ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Caller ID', 'disabled'])}}
                <br />

                {{Form::hidden('problem-id', $problem->id)}}
                {{Form::hidden('user-id', $user->id)}}

                {{Form::label('caller-name', 'Caller Name')}}
                <br />
                {{Form::text('caller-name', $user->forename.' '.$user->surname, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Caller Name', 'disabled'])}}
                <hr />

                <!-- Notes input -->
                {{Form::label('notes', 'Call Notes')}}
                <br />
                {{Form::textarea('notes', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Call Notes'])}}
                <br />

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

<script>
    $(document).ready(function () {
    });
</script>

@endsection
