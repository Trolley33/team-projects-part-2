@extends('layouts.app')
@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Assigned Problems for {{$user->forename}} {{$user->surname}}</h2>
    <table id='timeoff-table' class="display cell-border stripe hover" style="width:100%;">
        <thead>
            <tr>
                <th>Start Date</th><th>End Date</th><th>Reason</th><th>---</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timeoff as $to)
            <tr>
                <td>{{$to->startDate}}</td>
                <td>{{$to->endDate}}</td>
                <td>{{$to->reason}}</td>
                <td class="">
                    <i class="material-icons">delete</i>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

<script>
$(document).ready( function () 
{
    var timeoff = $('#timeoff-table').DataTable({
    });

});
</script>

@endsection
