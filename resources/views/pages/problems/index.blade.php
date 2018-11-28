@extends('layouts.app')

@section('content')
<table class="menu w3-center problem-table">
        <tr>
            <th> Problem ID </th> <th> Problem Type </th> <th> Time Logged </th> <th> Status </th> <th>View Problem</th>
        </tr>

        @foreach ($info as $problem)
        <tr>
            <td class="number">{{$problem->id}}</td>
            <td>{{$problem->problem_type}}</td> 
            <td>{{$problem->created_at}}</td> 
            <td> Ongoing </td>
            <td class="noPadding">
                <a href="problems/{{$problem->id}}"><button class="w3-button fill">View</button></a>
            </td>
        </tr>
        @endforeach
</table>
@endsection
