@extends('layouts.app')

@section('content')
<table class="menu w3-center problem-table">
        <tr>
            <th> Problem ID </th> <th> Problem Type </th> <th> Time Logged </th> <th> Status </th> <th>Edit Problem</th>
        </tr>
        <tr class="ongoing">
            <td class="number"> 1 </td> <td> General Printing </td> <td> 2018-11-12 09:30 </td> <td> Ongoing </td><td class="noPadding"><a href="problem-viewer.php/1"><button class="w3-button fill">Edit</button></a></td>
        </tr>
        <tr class="resolved">
            <td class="number"> 2 </td> <td> General Keyboard </td> <td> 2018-11-12 12:45 </td> <td> Resolved </td><td class="noPadding"><a href="problem-viewer/2"><button class="w3-button fill">Edit</button></a></td>
        </tr>
        <tr>
            <td class="number"> 3 </td> <td> Printer Queue Cancellation </td> <td> 2018-11-12 14:15 </td> <td> No Status Available </td><td class="noPadding"><a href="problem-viewer/3"><button class="w3-button fill">Edit</button></a></td>
        </tr>
</table>
@endsection
