@extends('layouts.app')

@section('content')
<style>
    .call_menu
    {
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        margin-bottom: 100px;
    }

    table{
        width: 90%;
        margin-left: 5%;
    }

    td{
        padding: 10px;
    }

    th{
        padding: 10px;
        background-color: lightgrey;
    }
</style>

<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$equipment->description}}</h2>
            {!! Form::open(['action' => ['EquipmentController@update', $equipment->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Serial Number</th>
                        <td>{{Form::text('serialNumber', $equipment->serial_number, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Serial No.'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Description</th>
                        <td>{{Form::text('desc', $equipment->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Model</th>
                        <td>{{Form::text('model', $equipment->model, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Model'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('_method', 'PUT')}}

            {{Form::submit('Submit Changes', ['class'=> "menu-item w3-card w3-button w3-row w3-teal"])}}

            {!! Form::close() !!}

            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function() 
{
    $('#back-btn').click(function()
    {
        // window.location.replace('/users/');
    })
});
</script>
@endsection
