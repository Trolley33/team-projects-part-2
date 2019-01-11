@extends('layouts.app')
@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Assigned Problems for {{$user->forename}} {{$user->surname}}</h2>
    <table id='problems-table' class="display cell-border stripe hover" style="width:100%;">
        <thead>
            <tr>
                <th>Problem Logged At</th><th>Problem Type</th><th>Problem Description</th><th>Importance</th><th>level</th><th>Initial Caller</th><th>---</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($problems as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>@if ($problem->pDesc != '0') ({{$problem->pDesc}}) @endif {{$problem->ptDesc}}</td><td>{{$problem->description}}</td><td class="{{$problem->class}}">{{$problem->text}}</td><td>{{$problem->level}}</td><td>{{$problem->forename}} {{$problem->surname}}</td>
                <td class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}';" style="text-align: center;">
                    View/Edit
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

</div>

<script>
$(document).ready( function () 
{
    var table = $('#problems-table').DataTable({
        order: [
          ['3', 'desc']
        ],
        "aoColumnDefs": [
            {
                "iDataSort": 4,
                "aTargets": [3] 
            },
            {
                "targets": [4],
                "visible": false,
                "searchable": false
            },
        ]
      } );

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
