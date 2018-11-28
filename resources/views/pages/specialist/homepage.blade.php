@extends('layouts.app')

@section('content')
<div class="w3-center menu">

    <div class="w3-padding w3-white" style='text-align:center'>
        <h3>Problem Related Tasks</h3><hr />
        <a class="blank" href="problems/">
            <div class="menu-item w3-card w3-button w3-row">
                View All Problems
            </div>
        </a><br />
    </div>
    <br />
    <div class="w3-padding w3-white" style='text-align:center'>
        <h3>Assigned Problems</h3><hr />
        <table>
            <tr>
                <th> Timestamp </th>
                <th> Caller ID </th>
                <th> Problem Type </th>
                <th> Notes </th>
            </tr>
            <tr id="1" class="w3-hover-light-grey solve">
                <td> 2018-10-24 17:55 </td>
                <td> 0001 </td>
                <td> Unresponsive Keyboard </td>
                <td> Not able to type on keyboard properly. </td>
            </tr>

        </table>
    </div>
</div>

<script>
    $('tr.solve').click(function() {
        location.href = "";
    })
</script>

@endsection
