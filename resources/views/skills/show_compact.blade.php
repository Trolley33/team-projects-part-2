@extends('layouts.compact')
@section('content')

<?php
$highlight = $_GET['skill'] ?? -1;
?>

<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Listed Skills For: {{$user->forename}} {{$user->surname}}</h2>
    <!-- List of skills for user -->
    <table id='skill-table' class="display cell-border stripe hover" style="width:100%;">
        <thead>
            <tr>
                <th>Problem Type</th><th>Ability</th><th>Edit Skill</th><th>Delete Skill</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($skills as $skill)
            <tr class="row" value='{{$skill->problem_type_id}}'>
                @if ($skill->problem_type_id == $highlight || $skill->parentID == $highlight)
                <td class="w3-text-green">
                @else
                <td>
                @endif
                @if (!is_null($skill->parentID))
                    ({{$skill->parentDesc}})
                @endif
                {{$skill->ptDesc}}
                </td>
                <td style="text-align: right;">{{$skill->ability}}/10</td>
                <!-- Option to edit each skill -->
                <td class="editbutton" style="text-align: center;" onclick="window.location.href = '/skills/{{$user->id}}/{{$skill->id}}/edit'">
                    Edit
                </td>
                <!-- Option to delete each skill -->
                <td class="editbutton w3-red" style="text-align: center;" onclick="$('#{{$skill->id}}').submit()">
                    Delete
                    {!!Form::open(['action' => ['SkillController@delete', $skill->id], 'method' => 'POST', 'onsubmit'=>"return confirmDelete()", 'id'=>$skill->id, 'hidden']) !!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {!!Form::close() !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="text-align: center;">
        <!-- Button to Add New Skill -->
        <a class="blank" href="/skills/{{$user->id}}/create">
            <div class="bigbutton w3-card w3-button w3-row">
                Add New Skill
            </div>
        </a><br />
    </div>
</div>

<script>
$(document).ready( function ()
{
    var skill = $('#skill-table').DataTable({
        order: [['1', 'desc']]
    });
});
//Pop up to confirm if you want to delete the skill
function confirmDelete() {
    return confirm("Really delete skill?");
}
</script>

@endsection
