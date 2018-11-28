@extends('layouts.app')

@section('content')
<div class="w3-center menu">

        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>Problem Related Tasks</h3><hr />
            <a class="blank" href="problems/">
                <div class="menu-item w3-card w3-button w3-row">
                    View All Problems
                </div>
            </a><br />
            <a class="blank" href="problems/create">
                <div class="menu-item w3-card w3-button w3-row">
                    Log New Call
                </div>
            </a><br />
            <a class="blank" href="users/">
                <div class="menu-item w3-card w3-button w3-row">
                    Find Caller Details
                </div>
            </a><br />
          </div>
        <br />
    </div>

@endsection
