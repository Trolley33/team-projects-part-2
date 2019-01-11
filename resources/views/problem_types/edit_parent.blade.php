@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Editor</h2>
                {!! Form::open(['action' => ['ProblemTypeController@update', $problem_type->id], 'method' => 'POST']) !!}
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Parent Problem Type</th>
                            <td><select id='parent-select' class="w3-input" disabled style="width: 100% !important;">
                                <option selected>None</option>
                                </select></td>

                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td>
                                {{Form::text('desc', $problem_type->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>$problem_type->description])}} 
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{Form::hidden('parent-select', '-1')}}
                {{Form::hidden('isParent', 'true')}}
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
        $('#parent-select').select2();
    });
    </script>
@endsection
