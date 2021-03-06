@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Call Viewer</h2>
            <!-- Call info -->
            <table id="info-table">
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Logged At</th>
                        <td> {{$call->created_at}} </td>
                    </tr>
                    <!-- Problem info -->
                    <tr class="w3-hover-light-grey">
                        <th>Related Problem ID</th>
                        <td class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}';">{{sprintf('%04d', $problem->id)}}<span class="icon">View</span></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Problem Type</th>
                        <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">
                            {{$problem_type->description}}<span class="icon">View</span>
                        </td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Problem Description</th>
                        <td>
                            {{$problem->description}}
                        </td>
                    </tr>
                    <!-- Caller info -->
                    <tr class="w3-hover-light-grey">
                        <th>Caller ID</th>
                        <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{sprintf('%04d', $user->employee_id)}}<span class="icon">View</span></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Caller Name</th>
                        <td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';">{{$user->forename}} {{$user->surname}}<span class="icon">View</span></td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Caller Phone Number</th>
                        <td>{{$user->phone_number}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Call Notes</th>
                        <td> {{$call->notes}} </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <!-- Form submitted when the Edit Call button is selected, that provides information about the relvant call in the next page, to edit details about the call -->
            {!!Form::open(['id'=>'editForm']) !!}

            {{Form::submit('Edit Call', ['class'=> "bigbutton w3-card w3-button w3-row but"])}}
            {!!Form::close() !!}

            {!!Form::open(['action' => ['CallsController@destroy', $call->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete Call? This action cannot be undone.');"]) !!}

            {{Form::hidden('_method', 'DELETE')}}

            {{Form::submit('Delete Call', ['class'=> "bigbutton w3-card w3-button w3-row but w3-red"])}}

            {!!Form::close() !!}
            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var first_call = <?php echo json_encode($first_call); ?>;

    var this_call = <?php echo json_encode($call); ?>

    // Disable buttons to edit and delete call, if this call is the first call for the problem
    if (first_call.id == this_call.id)
    {
        $('.but').prop('disabled', true);
    }

    // Redirects to page where you can edit call notes
    $('#editForm').submit(function () {
        window.location.href = "/calls/{{$call->id}}/edit";
        return false;
    });
});
</script>
@endsection
