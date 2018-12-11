@extends('layouts.app')

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

    #info-table{
        width: 90%;
        margin-left: 5%;
    }

    #info-table td{
        padding: 10px;
    }

    #info-table th{
        padding: 10px;
        background-color: lightgrey;
    }
    .editbutton:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
    .slideHeader:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Creator</h2>
                {!! Form::open(['action' => 'ProblemTypeController@create', 'method' => 'POST']) !!}
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Parent Problem</th>
                            <td></td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td>
                                {{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Equipment Description'])}} 
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{Form::submit('Submit', ['class'=>'w3-right w3-button w3-teal'])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {

    });
    </script>
@endsection
