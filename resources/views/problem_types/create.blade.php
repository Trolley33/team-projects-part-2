@extends('layouts.app')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Creator</h2>
                {!! Form::open(['action' => 'ProblemTypeController@store', 'method' => 'POST']) !!}
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey">
                            <th>Parent Problem Type</th>
                            <td><select id='parent-select' name='parent-select' class="w3-input" required  style="width: 100% !important;"></select></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td>{{Form::text('desc', '', ['required', 'class'=>'w3-input w3-border w3-round', 'placeholder'=>'Problem Type Description'])}}</td>
                        </tr>
                    </tbody>
                </table>
                {{Form::submit('Submit', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
        $('#parent-select').select2();

        var types = <?php echo json_encode($types) ;?>;

        var selected = <?php echo json_encode($selected);?>;

        $("#parent-select").append(new Option("None", '-1'));
        types.forEach(function (type)
        {
            var o;
            if (selected != null)
            {
                o = new Option(type.description, type.id, false, selected.id == type.id);
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
