@extends('layouts.app')

@section('content')

<div class="w3-padding-large w3-white">
        <form class="w3-container w3-white register w3-mobile" method="post" action="index.php">
            <span class="error"><?php if (isset($error)) echo $error; ?></span>
            <span class="success"><?php if (isset($success)) echo $success; ?></span>
            <br />
            <label>First Name</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="forename" placeholder="First Name" required/><br />
            <label>Last Name</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="surname" placeholder="Last Name" required/><br />
            <label>Job Title</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="job-title" placeholder="Job Title"/><br />
            <label>Department</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="department" placeholder="Department"/><br />
            <label>Select Role</label> <br />
            <select class='role w3-input'required>
                  <option>Helpdesk Operator</option>
                  <option>Helpdesk Specialist</option>
                  <option>Helpdesk Analyst</option>
            </select> <br /><br />
            <label>Username</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="username" placeholder="Username" required/><br />
            <label>Choose Password</label> <br />
            <input class="w3-input w3-border w3-round" type="password" name="password" placeholder="Password" required/><br />
            <label>Confirm Password</label> <br />
            <input class="w3-input w3-border w3-round" type="password" name="confirm-password" placeholder="Password" required/><br />
            <input class="w3-right w3-button w3-teal" type="submit" name="submit" value="Register"/><br /><br />
        </form>
</div>

<script>
    $(document).ready(function () {
        $('.role').select2();
    });
</script>

@endsection
