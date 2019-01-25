@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Register New Software</h2>
            {!! Form::open(['action' => 'SoftwareController@store', 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('name', 'Software Name')}}</th>
                        <td>{{Form::text('name', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Software Name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>{{Form::label('desc', 'Software Description')}}</th>
                        <td>{{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Software Description'])}}</td>
                    </tr>
                </tbody>
            </table>

            {{Form::submit('Submit Changes', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}

            {!! Form::close() !!}

            <br />
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    });
</script>

@endsection
