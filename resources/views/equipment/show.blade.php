@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <!-- Equipment name and details for equipment item selected in the previous page -->
            <h2>{{$equipment->description}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Serial Number</th>
                        <td>{{$equipment->serial_number}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Description</th>
                        <td>{{$equipment->description}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Model</th>
                        <td>{{$equipment->model}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Registered On</th>
                        <td>{{$equipment->created_at}}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Button to Edit Information about the current item of equipment -->
            <div style="text-align: center;">
                <a class="blank" href="/equipment/{{$equipment->id}}/edit">
                    <div class="w3-card w3-button w3-row bigbutton">
                        Edit Information
                    </div>
                </a><br />

                <!-- Form for editing selected equipment -->
                {!!Form::open(['action' => ['EquipmentController@destroy', $equipment->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete equipment? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}

                {{Form::submit('Delete Equipment', ['class'=> "bigbutton w3-card w3-button w3-row w3-red"])}}

                {!!Form::close() !!}
                <br />
            </div>

            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function()
{
});
</script>
@endsection
