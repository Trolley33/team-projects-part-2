@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Booking Time Off For: {{$user->forename}} {{$user->surname}}</h2>
            <table>
                {!!Form::open(['action' => ['PagesController@create_time_off'], 'method' => 'POST']) !!}

                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Start Date</th>
                        <td><input class="w3-input w3-border w3-round" type="date" id='start' name='start' min="{{date('Y-m-d')}}" required></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>End Date</th>
                        <td><input class="w3-input w3-border w3-round" type="date" id='end' name='end' disabled required></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Reason</th>
                        <td>{{Form::text('reason', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Reason', 'style'=>'resize: none;'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::submit('Submit Request', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
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
</script>
@endsection
