@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Viewer</h2>
                {!! Form::open(['action' => ['ProblemController@update', $problem->id], 'method' => 'POST']) !!}
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Number</th>
                            <td class="editbutton" onclick="window.location.href= '/problems/{{$problem->id}}'">{{sprintf('%04d', $problem->id)}}<span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type</th>
                            <td title="Edit" class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}/edit_problem_type';">
                                @if (!is_null($parent))
                                    ({{$parent->description}})
                                @endif
                                {{$problem_type->description}}
                                <span class="icon">Edit</span>
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td>{{Form::text('desc', $problem->description, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Notes</th>
                            <td>{{Form::textarea('notes', $problem->notes, ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Notes'])}}</td>
                        </tr>
                        <tr>
                            <th>Importance</th>
                            <td>
                                <select id="importance-select" name="importance" style="width: 100%;">
                                </select>
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Assigned Helper</th>
                            <td title="Edit" class="editbutton" onclick="window.location.href = '/problems/{{$problem->id}}/edit_specialist';">
                                @if (!is_null($specialist))
                                    {{$specialist->forename}} {{$specialist->surname}}
                                @else
                                    None
                                @endif
                            <span class="icon">Edit</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Status</th>
                            <?php $sol_notes = ''; ?>
                            @if (!is_null($resolved))
                                <?php $sol_notes = $resolved->solution_notes; ?>
                                <td title="Mark as Unsolved" id='toggleButton' class="editbutton w3-hover-light-green w3-green">
                                    Solved ({{$resolved->created_at}})
                                </td>
                                {{Form::hidden('solved', 'true', ['id'=>'solved'])}}
                            @else
                                <td title="Mark as Solved" id='toggleButton' class="editbutton w3-hover-deep-orange w3-red">
                                    Unsolved
                                </td>
                                {{Form::hidden('solved', 'false', ['id'=>'solved'])}}
                            @endif
                        </tr>
                        <tr id='solutionNotes' class="w3-hover-light-grey">
                            <th>Solution Notes</th>
                            <td>
                                
                            {{Form::text('solution_notes', $sol_notes, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Solution Notes'])}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{Form::hidden('_method', 'PUT')}}

                {{Form::submit('Submit Changes', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {

        $('#importance-select').select2();

        var importance = <?php echo json_encode($importance) ?>;
        var currentImportanceID = <?php echo json_encode($problem->importance); ?>;

        importance.forEach(function (i) {
            var o = new Option(i.text, i.id, false, currentImportanceID == i.id);
            $("#importance-select").append(o);
        });

        if ($('#solved').val() == 'false')
        {
            $('#solutionNotes').hide();
        }
        else if ($('#solved').val() == 'true')
        {
            $('#solutionNotes').show();
        }


        $('#toggleButton').click(function () {
            // Mark as unsolved.
            if ($('#solved').val() == 'true')
            {
                $('#solved').val('false');
                $('#toggleButton').removeClass('w3-green w3-hover-light-green');
                $('#toggleButton').addClass('w3-hover-deep-orange w3-red');
                $('#toggleButton').html('Unsolved');
                $('#solutionNotes').hide();


            }
            // Mark as solved.
            else if ($('#solved').val() == 'false')
            {
                $('#solved').val('true');
                $('#toggleButton').removeClass('w3-hover-deep-orange w3-red');
                $('#toggleButton').addClass('w3-green w3-hover-light-green');
                $('#toggleButton').html('Solved');
                $('#solutionNotes').show();
            }
        });
    });
    </script>
@endsection
