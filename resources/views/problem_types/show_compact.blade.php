@extends('layouts.compact')
@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div class="compact">
            <div class="w3-padding-large w3-white">
                <h2>Problem Type Viewer</h2>
                <table>
                    <tbody>
                        @if (!is_null($parent))
                            <tr class="w3-hover-light-grey">
                                <th>Parent Problem ID</th>
                                <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">{{sprintf('%04d', $parent->id)}}<span class="icon">View</span></td>
                            </tr>
                            <tr class="w3-hover-light-grey">
                                <th>Parent Problem Description</th>
                                <td class="editbutton" onclick="window.location.href = '/problem_types/{{$parent->id}}';">{{$parent->description}}<span class="icon">View</span></td>
                            </tr>
                        @endif
                        <tr class="w3-hover-light-grey">
                            <th>Problem Type ID</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$type->id}}';">{{sprintf('%04d', $type->id)}}<span class="icon">View</span></td>
                        </tr>
                        <tr class="w3-hover-light-grey">
                            <th>Description</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$type->id}}';">
                                {{$type->description}}<span class="icon">View</span> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- specialists -->
                <h2 class="slideHeader">Specialists</h2>
                <div class="slideable">
                    <table id='specialist-table' class="display cell-border stripe hover slidable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Employee ID</th><th>Full Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specialists as $specialist)
                            <tr>
                                <td>{{sprintf('%04d', $specialist->employee_id)}}</td>
                                <td>{{$specialist->forename}} {{$specialist->surname}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function () {
        $('#specialist-table').DataTable();

        var level = <?php echo $level; ?>;

        if (level == 2) {
            $('.compact .editbutton').attr('onclick', '');
            $('.compact .editbutton').removeClass('editbutton');
        }
    });
    </script>
@endsection