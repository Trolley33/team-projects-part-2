@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Request Problem #{{sprintf('%04d', $problem->id)}} be Re-assigned?</h2>
            <table id="info-table">
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <td class="editbutton w3-green" onclick="window.location.href = '/problems/{{$problem->id}}/remove_specialist';"><b>Yes</b></th>
                        <td class="editbutton w3-red" onclick="window.location.href = '/problems/{{$problem->id}}';"><b>No</b></td>
                    </tr>
                </tbody>
            </table>    
        </div>
    </div>
</div>
@endsection
