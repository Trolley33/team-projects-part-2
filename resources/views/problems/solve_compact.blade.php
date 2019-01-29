@extends('layouts.compact')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div class="compact">
            <div class="w3-padding-large w3-white">
                <h2>Mark Problem {{sprintf('%04d', $problem->id)}} as solved?</h2>
                {!! Form::open(['action' => ['ProblemController@solve_problem', $problem->id], 'method' => 'POST']) !!}
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Solution Notes</th>
                            <td>
                                {{Form::textarea('notes', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Notes', 'style'=>'resize: none;'])}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{Form::submit('Solve Problem', ['class'=> "bigbutton w3-card w3-button w3-row w3-green"])}}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
    });
    </script>
@endsection