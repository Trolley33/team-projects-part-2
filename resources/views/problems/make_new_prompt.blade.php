@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Create Another Problem for {{$caller->forename}} {{$caller->surname}}?</h2>
            <div class="bigbutton w3-card w3-button w3-row w3-teal" onclick="window.location.href = '/problems/create/{{$caller->id}}';"><b>Log Another Call</b></div>
            <div class="bigbutton w3-card w3-button w3-row w3-teal" onclick="window.location.href = '/problems/{{$problem->id}}';"><b>View Created Problem</b></div>
        </div>
    </div>
</div>
@endsection
