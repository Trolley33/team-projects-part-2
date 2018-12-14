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
                <th>Time Logged</th><th>Assigned Helper</th><th>Problem Type</th><th>Description</th><th>Initial Caller</th><th>Status</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($problems as $problem)
            <tr>
                <td>{{$problem->created_at}}</td><td>@if ($problem->sID != 0)
                    {{$problem->sForename}} {{$problem->sSurname}}
                @else
                    None
                @endif
                </td><td>@if ($problem->pDesc != '0') ({{$problem->pDesc}}) @endif {{$problem->ptDesc}}</td><td>{{$problem->description}}</td><td>{{$problem->forename}} {{$problem->surname}}</td>
                <?php $flag = true; ?>
                    @foreach($resolved as $res)
                        @if ($res->problem_id == $problem->pID)
                            <td class="w3-green">
                                Resolved
                                <?php $flag = false; ?>
                            </td>
                            @break
                        @endif
                    @endforeach
                    @if ($flag)
                        <td class="w3-red">
                            Ongoing
                        </td>
                    @endif
                <td class="editbutton" value='{{$problem->pID}}' style="text-align: center;">
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
    var table = $('#problem-table').dataTable({
        order: [[5, 'asc'], [0, 'desc']]
    });
    $('.editbutton').click(function() 
    {
        window.location.href = '/problems/' + $(this).attr('value');
    });
});
</script>

@endsection
