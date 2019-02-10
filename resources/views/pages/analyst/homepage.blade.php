@extends('layouts.app')

@section('content')
<div class="">
        <!-- Analyst home page with links to relevant pages -->
        <div class="call_menu w3-center w3-padding" style='text-align:center'>
            <h3>Data Reviews</h3><hr />
            <a class="blank" href="/review/specialists">
                <div class="bigbutton w3-card w3-button w3-row">
                    Review Helpers
                </div>
            </a><br />
            <a class="blank" href="/review/callers">
                <div class="bigbutton w3-card w3-button w3-row">
                    Review Caller
                </div>
            </a><br />
            <a class="blank" href="/review/equipment">
                <div class="bigbutton w3-card w3-button w3-row">
                    Review Equipment
                </div>
            </a><br />
            <a class="blank" href="/review/software">
                <div class="bigbutton w3-card w3-button w3-row">
                    Review Software
                </div>
            </a><br />
            <a class="blank" href="/review/problem_types">
                <div class="bigbutton w3-card w3-button w3-row">
                    Review Problem Types
                </div>
            </a><br />
            <hr>
            <a class="blank" href="/analyst/export">
                <div class="bigbutton w3-card w3-button w3-row">
                    Export Data
                </div>
            </a><br />
          </div>
    </div>

    <script>
    $(document).ready( function ()
    {
    });
    </script>
@endsection
