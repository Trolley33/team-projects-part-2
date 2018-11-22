@extends('layouts.app')

@section('content')

<div class="w3-padding-large w3-white">
        <form class="w3-container w3-white register w3-mobile" method="post" action="index.php">
            <span class="error"><?php if (isset($error)) echo $error; ?></span>
            <span class="success"><?php if (isset($success)) echo $success; ?></span>
            <br />
            <label>Name</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="name" placeholder="Name" value="{{$username}}" required/><br />
            <label>ID Number</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="IDNumber" placeholder="ID Number" required/><br />
            <label>Job Title</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="job-title" placeholder="Job Title"/><br />
            <label>Department</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="department" placeholder="Department"/><br />
            <label>Email Address</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="email-address" placeholder="Email Address" required/><br />
            <label>Select Role</label> <br />
            <select class="problem-select" required>
                  <option>Operator</option>
                  <option>Technician</option>
                  <option>Analyst</option>
            </select> <br />
            <label>Username</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="username" placeholder="Username" required/><br />
            <label>Select Password</label> <br />
            <input class="w3-input w3-border w3-round" type="password" name="password" placeholder="Password" required/><br />
            <label>Confirm Password</label> <br />
            <input class="w3-input w3-border w3-round" type="password" name="confirm-password" placeholder="Password" required/><br />
            <input class="w3-right w3-button w3-teal" type="submit" name="submit" value="Log in"/><br /><br />
        </form>

</div>

@endsection
