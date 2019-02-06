@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Editing Time Off For: {{$user->forename}} {{$user->surname}}</h2>
            <table>
                {!!Form::open(['action' => ['PagesController@update_absence', $timeoff->id], 'method' => 'POST']) !!}

                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Start Date</th>
                        <td><input class="w3-input w3-border w3-round" type="date" id='start' name='start' min="{{date('Y-m-d')}}" value="{{$timeoff->startDate}}"></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>End Date</th>
                        <td><input class="w3-input w3-border w3-round" type="date" id='end' name='end' value="{{$timeoff->endDate}}"></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Reason</th>
                        <td>{{Form::text('reason', $timeoff->reason, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Reason', 'style'=>'resize: none;'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('_method', 'PUT')}}

            {{Form::submit('Edit Absence', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!!Form::close() !!}
            
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#start').change(function () {
        var startDate = new Date($('#start').val());
        if (isNaN(startDate.getTime())) {
            $('#end').prop('disabled', true);
            $('#end').val($('#start').val());
        }
        else if ($('#start').val() >= $('#end').val()) {
            endTime = startDate.getTime() + (1000*60*60*24);
            endDate = new Date(endTime);
            $('#end').val(endDate.getFullYear() + "-" + zfill(endDate.getMonth() + 1, 2) + "-" + zfill(endDate.getDate(), 2));
            $('#end').attr('min', $('#end').val());
            $('#end').prop('disabled', false);
        }
        else {
            minTime = startDate.getTime() + (1000*60*60*24);
            minDate = new Date(minTime);
            $('#end').attr('min', minDate.getFullYear() + "-" + zfill(minDate.getMonth() + 1, 2) + "-" + zfill(minDate.getDate(), 2));
        }
    });
});
// Zero padding function for YYYY-MM-DD
// *(https://stackoverflow.com/a/7379989)
function zfill(num, len) {
    return (Array(len).join("0") + num).slice(-len);
}
</script>
@endsection
