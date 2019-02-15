@extends('layouts.app')

@section('content')
<div class="">
    <!-- List of links for specialist to use, functionally same as navbar but gives homepage a bit of bulk -->
        <div class="call_menu w3-center w3-padding" style='text-align:center'>
            <h3>Main Links</h3><hr />
            <a class="blank" href="/problems/">
                <div class="bigbutton w3-card w3-button w3-row">
                    View Assigned Problems
                </div>
            </a><br />
            <a class="blank" href="/specialist/timeoff">
                <div class="bigbutton w3-card w3-button w3-row">
                    Manage Time Off
                </div>
            </a><br />
            <a class="blank" href="/users/{{$user->id}}/edit_specialism">
                <div class="bigbutton w3-card w3-button w3-row">
                    Edit Specialism
                </div>
            </a><br />
            <a class="blank" href="/skills/{{$user->id}}">
                <div class="bigbutton w3-card w3-button w3-row">
                    Edit Skills
                </div>
            </a><br />
          </div>
        <br />
    </div>

    <script>
    $(document).ready(function () {
    });
    </script>
@endsection
