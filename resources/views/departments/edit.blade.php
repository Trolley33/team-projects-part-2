@extends('layouts.app')

@section('content')
<br />
        {!! Form::open(['action' => ['DepartmentController@update', $department->id], 'method' => 'POST']) !!}
            <div class="w3-container w3-white login w3-mobile">
                <span class="error"><?php if (isset($error)) echo $error; ?></span>
                <span class="success"><?php if (isset($success)) echo $success; ?></span>
                <br />
                {{Form::label('', 'Previous Name')}}
                <br/>
                {{Form::text('', $department->name, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Department Name', 'disabled'])}}
                <br />
                {{Form::label('deptName', 'New Name')}}
                <br/>
                {{Form::text('deptName', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Department Name'])}}
                <br />

                {{Form::hidden('_method', 'PUT')}}

                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
            </div>
        {!! Form::close() !!}

        

<script>
    $(document).ready(function () {
    });
</script>

@endsection
