@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$equipment->description}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Serial Number</th>
                        <td>{{$equipment->serial_number}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Description</th>
                        <td>{{$equipment->description}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Model</th>
                        <td>{{$equipment->model}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Registered On</th>
                        <td>{{$equipment->created_at}}</td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a class="blank" href="/equipment/{{$equipment->id}}/edit">
                    <div class="menu-item w3-card w3-button w3-row">
                        Edit Information
                    </div>
                </a><br />

                {!!Form::open(['action' => ['EquipmentController@destroy', $equipment->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete equipment? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}
                
                {{Form::submit('Delete Equipment', ['class'=> "menu-item w3-card w3-button w3-row w3-red"])}}
                
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
