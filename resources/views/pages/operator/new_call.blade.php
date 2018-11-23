@extends('layouts.app')

<style>
    .call_menu{
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        
    }
    
    table{
    }
    
    td{
        padding: 10px;
    }
    
    th{
        padding: 10px;
        background-color: lightgrey;
    }
    </style>

@section('content')

<div class="call_menu w3-center w3-padding w3-light-grey">
    <h2>Active Problems</h2>
    <div>
        <div class="w3-padding-large w3-white" style="text-align:center">
            <table>
                <tbody>
                    <tr w3-light-grey>
                        <th> Problem Number </th>
                        <th> Caller ID </th>
                        <th> Problem Type </th>
                        <th> Notes </th>
                    </tr>
                    <tr id="1" class="w3-hover-light-grey solve">
                        <td> #1 </td>
                        <td> 0001 </td>
                        <td> Unresponsive Keyboard </td>
                        <td> Not able to type on keyboard properly. </td>
                        <td><button class="w3-button fill w3-light-grey"><a class="blank" href="../operator/problem_viewer.php">Add Details</a></button></td>
                    </tr>
                    <tr id="2" class="w3-hover-light-grey solve">
                        <td> #23 </td>
                        <td> 0001, 0024 </td>
                        <td> Network Error </td>
                        <td> Cannot connect to company internet. </td>
                        <td><button class="w3-button fill  w3-light-grey"><a class="blank" href="../operator/problem_viewer.php">Add Details</a></button></td>
                    </tr>
                    <tr id="3" class="w3-hover-light-grey solve">
                        <td> #3 </td>
                        <td> 0007 </td>
                        <td> Monitor Failure </td>
                        <td> PC monitor won't power on. </td>
                        <td><button class="w3-button fill  w3-light-grey"><a class="blank" href="../operator/problem_viewer.php">Add Details</a></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w3-padding-large w3-white" style="text-align:center">
        <a class="blank" href="../operator/problem_viewer_empty_example.php">
            <input class="w3-card w3-btn w3-row w3-light-grey" value="New Problem + ">
        </a>
        </div>
    </div>
</div>

@endsection
