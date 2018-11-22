@extends('layouts.app')

@section('content')
<div class="w3-center menu">

        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>Problem Related Tasks</h3><hr />
            <a class="blank" href="view-problems">
                <div class="menu-item w3-card w3-button w3-row">
                    View All Problems
                </div>
            </a><br />
            <a class="blank" href="log-call">
                <div class="menu-item w3-card w3-button w3-row">
                    Log New Call
                </div>
            </a><br />
            <a class="blank" href="caller-info">
                <div class="menu-item w3-card w3-button w3-row">
                    Find Caller Details
                </div>
            </a><br />
          </div>
        <br />
        <div class="w3-padding w3-white" style='text-align:center'>
            <h3>Submitted Problems (Awaiting Approval)</h3><hr />
            <table>
                <tr>
                    <th> Timestamp </th>
                    <th> Caller ID </th>
                    <th> Problem Type </th>
                    <th> Notes </th>
                </tr>

            <tr class="w3-hover-light-grey approve">
                    <td> 2018-10-24 18:24 </td>
                    <td> 0003 </td>
                    <td> Unresponsive Computer </td>
                    <td> PC turned off and won't turn back on. </td>
                </tr>
                </a>
            </table>
        </div>
    </div>

    <script>
        $('tr.approve').click(function() {
            location.href = "../problems/create_problem.php?ID=" + $(this).attr('id');
        })
    </script>
@endsection
