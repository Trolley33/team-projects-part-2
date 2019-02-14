@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Editor</h2>
                <!-- Form to edit problem type -->
                {!! Form::open(['action' => ['ProblemTypeController@update', $problem_type->id], 'method' => 'POST']) !!}
                <table>
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Parent Problem Type</th>
                            <td><select id='parent-select' name='parent-select' class="w3-input" required style="width: 100% !important;">
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
                {{Form::hidden('isParent', 'false')}}
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
        $('#parent-select').select2();

        var types = <?php echo json_encode($types) ;?>;

        var selected = <?php echo json_encode($problem_type);?>;
        //List of Exisiting problem types added to drop down
        types.forEach(function (type)
        {
            var o;
            if (selected != null)
            {
                o = new Option(type.description, type.id, false, selected.parent == type.id);
            }
            else
            {
                o = new Option(type.description, type.id);
            }

            $("#parent-select").append(o);
        });
    });
    </script>
@endsection
