@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Creator</h2>
                <hr />
                <h3>Creating problem for: {{$user->forename}} {{$user->surname}}</h3>
                {!! Form::open(['action' => ['ProblemController@select_specialist_for_problem', $user->id, $problem_type->id], 'method' => 'POST']) !!}
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type</th>
                            <td title="Edit" class="editbutton" onclick="window.location.href = '/problems/create/{{$user->id}}';">
                                @if (!is_null($parent))
                                    ({{$parent->description}}) 
                                @endif
                                {{$problem_type->description}}
                                <span class="icon">Edit</span>
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td>{{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Notes</th>
                            <td>{{Form::textarea('notes', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Notes'])}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Importance</th>
                            <td>
                                <select id="importance-select" name="importance" style="width: 100%;">
                                    @foreach ($importance as $i)
                                        <option value="{{$i->id}}">{{$i->text}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{Form::submit('Assign Specialist', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $('#importance-select').select2();
    });
    </script>
@endsection
