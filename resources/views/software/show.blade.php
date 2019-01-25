@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$software->description}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Software ID</th>
                        <td>{{sprintf('%04d', $software->id)}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Name</th>
                        <td>{{$software->name}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Description</th>
                        <td>{{$software->description}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Registered On</th>
                        <td>{{$software->created_at}}</td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a class="blank" href="/software/{{$software->id}}/edit">
                    <div class="bigbutton w3-card w3-button w3-row">
                        Edit Information
                    </div>
                </a><br />

                {!!Form::open(['action' => ['SoftwareController@destroy', $software->id], 'method' => 'POST', 'onsubmit'=>"return confirm('Delete software? This action cannot be undone.');"]) !!}

                {{Form::hidden('_method', 'DELETE')}}
                
                {{Form::submit('Delete Software', ['class'=> "bigbutton w3-card w3-button w3-row w3-red"])}}
                
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
    $('#back-btn').click(function()
    {
        // window.location.replace('/users/');
    })
});
</script>
@endsection
