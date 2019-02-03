@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Request Problem {{sprintf('%04d', $problem->id)}} be Re-assigned?</h2>
            <table>
                {!!Form::open(['action' => ['ReassignmentController@request_reassignment', $problem->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Reassign Problem? This action cannot be undone.');"]) !!}

                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Problem Description</th>
                        <td>{{$problem->description}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Problem Notes</th>
                        <td>{{$problem->notes}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Reason</th>
                        <td>{{Form::textarea('reason', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Reason', 'style'=>'resize: none;'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::submit('Yes', ['class'=> "bigbutton w3-card w3-button w3-row w3-green"])}}
            
            {!!Form::close() !!}
            <div class="bigbutton w3-card w3-button w3-row w3-red" onclick="window.location.href = '/problems/{{$problem->id}}';"><b>No</b></div>
            
        </div>
    </div>
</div>
@endsection
