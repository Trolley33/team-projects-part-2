@extends('layouts.app')

@section('content')
<div class="">
        <div class="call_menu w3-center w3-padding" style='text-align:center'>
            <h3>Main Links</h3><hr />
            <a class="blank" href="/problems/">
                <div class="bigbutton w3-card w3-button w3-row">
                    View Assigned Problems
                </div>
            </a><br />
            <a class="blank" href="/specialist/book_absence">
                <div class="bigbutton w3-card w3-button w3-row">
                    Book Absence
                </div>
            </a><br />
            <a class="blank" href="/users/{{$user->id}}/edit_specialism">
                <div class="bigbutton w3-card w3-button w3-row">
                    Edit Specialism
                </div>
            </a><br />
            <a class="blank todo" href="">
                <div class="bigbutton w3-card w3-button w3-row">
                    Edit Experience
                </div>
            </a><br />
          </div>
        <br />
    </div>

    <script>
    $(document).ready(function () {
        $('.todo').click(function () {
            alert('Not implemented.');
        });
    });
    </script>
@endsection
