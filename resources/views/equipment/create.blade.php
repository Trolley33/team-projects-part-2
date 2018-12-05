@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => 'EquipmentController@store', 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                {{Form::label('serialNumber', 'Serial Number')}}
                <br />
                {{Form::text('serialNumber', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Serial Number'])}}
                <br />

                {{Form::label('desc', 'Equipment Description')}}
                <br />
                {{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Equipment Description'])}}
                <br />

                {{Form::label('model', 'Equipment Model')}}
                <br />
                {{Form::text('model', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Equipment Model'])}}
                <br />

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

<script>
    $(document).ready(function () {
    });
</script>

@endsection
