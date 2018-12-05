@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => 'SoftwareController@store', 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />

                {{Form::label('name', 'Software Name')}}
                <br />
                {{Form::text('name', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Software Name'])}}
                <br />
                {{Form::label('desc', 'Software Description')}}
                <br />
                {{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Software Description'])}}
                <br />

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

<script>
    $(document).ready(function () {
    });
</script>

@endsection
