@extends('layouts.app')

<style>
.editbutton:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}
</style>

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <table id='problem-table' class="display cell-border stripe hover">
        <thead>
            <tr>
                <th>Problem ID</th><th>Problem Type</th><th>Time Logged</th><th>Status</th><th>---</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($info as $problem)
            <tr>
                <td>{{sprintf('%04d',$problem->id)}}</td><td>{{$problem->problem_type}}</td><td>{{$problem->created_at}}</td>
                <td>
                    Ongoing
                </td>
                <td class="editbutton" value='{{$problem->id}}' style="text-align: center;">
                    View/Edit
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

    <div style="text-align: center;">
        <a class="blank" href="problems/create">
            <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                Create New Problem
            </div>
        </a><br />
    </div>
</div>


<script>
$(document).ready( function () 
{
    var table = $('#problem-table').DataTable();
    $('.editbutton').click(function() 
    {
        window.location.href = '/problems/' + $(this).attr('value');
    });

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
