<style>
    .call_menu
    {
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        margin-bottom: 100px;
    }

    table{
        width: 90%;
        margin-left: 5%;
    }

    td{
        padding: 10px;
    }

    th{
        padding: 10px;
        background-color: lightgrey;
    }

    .editbutton:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>

@extends('layouts.app')

@section('content')
<br />
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$user->forename}} {{$user->surname}} - ID: {{sprintf('%04d',$user->employee_id)}}</h2>
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey solve">
                        <th>Employee ID</th>
                        <td>{{$user->employee_id}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Username</th>
                        <td>{{$user->username}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Password</th>
                        <td>{{$user->password}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Full Name</th>
                        <td>{{$user->forename}} {{$user->surname}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Job Title</th>
                        <td><select id='job-select' name='job-select' class="w3-input" required  style="width: 100% !important;">
                        </select></td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Phone Number</th>
                        <td>{{$user->phone_number}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey solve">
                        <th>Account Creation Date</th>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    @if (!is_null($problem_type))
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Specialism</th>
                            <td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';">{{$problem_type->description}}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <br />
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#job-select').select2();

        var pass = '<?php echo $user->password ?>';
        $('#password').val(pass);
        $('#password2').val(pass);
        $('#pass-visible').change(function()
        {
            if (!this.checked)
            {
                $('#password').attr('type', 'password');
                $('#password2').attr('type', 'password');
            }
            else
            {
                $('#password').attr('type', 'text');
                $('#password2').attr('type', 'text'); 
            }
        });

        
        $('#techForm').submit(function ()
        {
            // Verify form content.
            var flag = true;
            $('#messages').html("");
            if ($('#password').val() != $('#password2').val())
            {
                $('#messages').append("<div class='w3-red' style='width: 100%;'>Passwords do not match.</div>");
                flag = false;
            }

            return flag;
        });
    });
</script>

@endsection