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
                <h2>Problem Viewer</h2>
                {!! Form::open(['action' => ['ProblemController@update', $problem->id], 'method' => 'POST']) !!}
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Number</th>
                            <td> #{{$problem->id}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td>
                                {{$problem->problem_type}}
                                <!-- do problem type selection -->
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td>{{Form::text('desc', $problem->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td>{{Form::textarea('notes', $problem->notes, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Notes'])}}</td>
                        </tr>
                        <!-- specialist choosing
                        <tr class="w3-hover-light-grey solve">
                            <th>Assigned Helper</th>
                            <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';">{{$specialist->forename}} {{$specialist->surname}}</td>
                        </tr>
                        -->
                        <tr class="w3-hover-light-grey solve">
                            <th>Status</th>
                            @if (count($resolved) === 1)

                                <td class="w3-green" > Solved 
                                </td>
                            @else
                                <td class="w3-red" > Unsolved </td>
                            @endif
                        </tr>
                        @if (count($resolved) === 1)
                            <tr class="w3-hover-light-grey solve">
                                <th>Solution Notes</th>
                                <td>
                                @foreach ($resolved as $r)
                                    {{Form::text('solution_notes', $r->solution_notes, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Solution Notes'])}}
                                @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{Form::hidden('_method', 'PUT')}}

                {{Form::submit('Submit Changes', ['class'=> "menu-item w3-card w3-button w3-row w3-teal"])}}
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
    });
    </script>
@endsection
