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
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Creator</h2>
                <h3>Creating problem for: {{$user->forename}} {{$user->surname}}</h3>
                {!! Form::open(['action' => ['ProblemController@select_specialist_for_problem', $user->id, $problem_type->id], 'method' => 'POST']) !!}
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td title="Edit" class="editbutton" onclick="window.location.href = '/problems/create/{{$user->id}}';">
                                {{$problem_type->description}}
                                <span class="icon">Edit</span>
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td>{{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td>{{Form::textarea('notes', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Notes'])}}</td>
                        </tr>
                    </tbody>
                </table>
                {{Form::submit('Assign Specialist', ['class'=> "menu-item w3-card w3-button w3-row w3-teal"])}}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
    });
    </script>
@endsection