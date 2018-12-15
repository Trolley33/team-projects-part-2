@extends('layouts.app')

@section('content')
<div class="">
        <div class="w3-container w3-white login w3-mobile" style='text-align:center'>
            <h3>New Call</h3><hr />
            <a class="blank" href="calls/create">
                <div class="menu-item w3-card w3-button w3-row">
                    Log New Call
                </div>
            </a><br />
        </div>

        <div class="w3-container w3-white login w3-mobile" style='text-align:center'>
            <h3>Database Management Options</h3><hr />
            <a class="blank" href="/problems/">
                <div class="menu-item w3-card w3-button w3-row">
                    Problem Manager
                </div>
            </a><br />
            <a class="blank" href="/users/">
                <div class="menu-item w3-card w3-button w3-row">
                    User Manager
                </div>
            </a><br />
            <a class="blank" href="/departments/">
                <div class="menu-item w3-card w3-button w3-row">
                    Department Manager
                </div>
            </a><br />
            <a class="blank" href="/jobs/">
                <div class="menu-item w3-card w3-button w3-row">
                    Job Manager
                </div>
            </a><br />
            <a class="blank" href="/equipment/">
                <div class="menu-item w3-card w3-button w3-row">
                    Equipment Manager
                </div>
            </a><br />
            <a class="blank" href="/software/">
                <div class="menu-item w3-card w3-button w3-row">
                    Software Manager
                </div>
            </a><br />
            <a class="blank" href="/problem_types/">
                <div class="menu-item w3-card w3-button w3-row">
                    Problem Type Manager
                </div>
            </a><br />
          </div>
        <br />
    </div>

<script>
$(document).ready(function() 
{
    $('#back-btn').html("Logout");
    $('#back-btn').click(function()
    {
        window.location.replace('/logout');
    })
});
</script>

@endsection
