@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Editing Time Off For: {{$user->forename}} {{$user->surname}}</h2>
            <!-- Table of form entries -->
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
    // When starting datapicker is changed:
    $('#start').change(function () {
        // Get selected date as string.
        var startDate = new Date($('#start').val());
        // If no date is selected, disable end date picker and set as same value (blank).
        if (isNaN(startDate.getTime())) {
            $('#end').prop('disabled', true);
            $('#end').val($('#start').val());
        }
        // If date is valid, and the new start data is greater than the old end date.
        else if ($('#start').val() >= $('#end').val()) {
            // Set end date as 1 day after new start date.
            endTime = startDate.getTime() + (1000*60*60*24);
            endDate = new Date(endTime);
            $('#end').val(endDate.getFullYear() + "-" + zfill(endDate.getMonth() + 1, 2) + "-" + zfill(endDate.getDate(), 2));
            $('#end').attr('min', $('#end').val());
            $('#end').prop('disabled', false);
        }
        // Valid date, but before end date.
        else {
            // Set minimum selectable date to be the day after the startDate.
            minTime = startDate.getTime() + (1000*60*60*24);
            minDate = new Date(minTime);
            $('#end').attr('min', minDate.getFullYear() + "-" + zfill(minDate.getMonth() + 1, 2) + "-" + zfill(minDate.getDate(), 2));
        }
    });
});

</script>
@endsection
