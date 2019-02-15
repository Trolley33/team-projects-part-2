@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <!-- Name of specialist skill is being added for -->
            <h2>Adding Skill For: {{$user->forename}} {{$user->surname}}</h2>
            <table>
                {!!Form::open(['action' => ['SkillController@store', $user->id], 'method' => 'POST']) !!}

                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Selected Skill Type</th>
                        <td class="editbutton" onclick="window.location.href = '/skills/{{$user->id}}/create'">
                            @if (!is_null($parent))
                                ({{$parent->description}})
                            @endif
                            {{$problem_type->description}}
                            <span class="icon">Edit</span></td>
                    </tr>
                    <!-- Select ability of specialist on scale of 0-10 for this skill -->
                    <tr class="w3-hover-light-grey">
                        <th>Ability</th>
                        <td>{{Form::number('ability', '', ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'0-10', 'min'=>'0', 'max'=>'10', 'required'])}}</td>
                    </tr>
                </tbody>
            </table>
            {{Form::hidden('problem_type', $problem_type->id)}}
            {{Form::submit('Submit Skill', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            {!!Form::close() !!}

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
});
</script>
@endsection
