@extends('layouts.app')
@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Planned Time Off For: {{$user->forename}} {{$user->surname}}</h2>
    <!-- Table of upcoming time off for selected specialist -->
    <table id='timeoff-table' class="display cell-border stripe hover" style="width:100%;">
        <thead>
            <tr>
                <th>Start Date</th><th>End Date</th><th>Reason</th><th>Edit Time Off</th><th>Delete Time Off</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timeoff as $to)
            <tr>
                <td>{{$to->startDate}}</td>
                <td>{{$to->endDate}}</td>
                <td>{{$to->reason}}</td>
                <td class="editbutton" style="text-align: center;" onclick="window.location.href = '/specialist/timeoff/{{$to->id}}/edit'">
                    Edit
                </td>
                <td class="editbutton w3-red" style="text-align: center;" onclick="$('#{{$to->id}}').submit()">
                    Delete
                    {!!Form::open(['action' => ['PagesController@delete_absence', $to->id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>$to->id, 'hidden']) !!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {!!Form::close() !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="text-align: center;">
        <a class="blank" href="/specialist/timeoff/book_absence">
            <div class="bigbutton w3-card w3-button w3-row">
                Book Time Off
            </div>
        </a><br />
    </div>
</div>

<script>
$(document).ready( function () 
{
    var timeoff = $('#timeoff-table').DataTable({
    });
});

function confirmDelete() {
    return confirm("Really delete booked absence?");
}
</script>

@endsection
