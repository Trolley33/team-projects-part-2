@extends('layouts.app')

@section('content')
    <div class="">
        <form class="w3-container w3-white login w3-mobile" method="post">
            @csrf
            <input type='hidden' name='tok' value = '<?php echo base64_encode(openssl_random_pseudo_bytes(16)); ?>' />
            <label>Username</label> <br />
            <input class="w3-input w3-border w3-round" type="text" name="username" placeholder="Username"/><br />
            <label>Password</label> <br />
            <input class="w3-input w3-border w3-round" type="password" name="password" placeholder="Password"/><br />
            <input class="w3-right w3-button w3-teal" type="submit" name="submit" value="Log in" formaction="verify"/>
        </form>
    </div>
<div class="center-box w3-container w3-center">
     <!-- spoofed logins for quick access -->

    <form method="post">
            @csrf
            <input type='hidden' name='tok' value = '<?php echo base64_encode(openssl_random_pseudo_bytes(16)); ?>' />
            <input type="hidden" name="username" value="alice" /><br />
            <input type="hidden" name="password" value="password" /><br />
            <input class="bigbutton w3-card w3-button w3-row w3-white" type="submit" name="submit" value="Log in as Alice (operator)" formaction="verify"/>
    </form>

    <form method="post">
            @csrf
            <input type='hidden' name='tok' value = '<?php echo base64_encode(openssl_random_pseudo_bytes(16)); ?>' />
            <input type="hidden" name="username" value="terry" /><br />
            <input type="hidden" name="password" value="password" /><br />
            <input class="bigbutton w3-card w3-button w3-row w3-white" type="submit" name="submit" value="Log in as Terry (specialist)" formaction="verify"/>
    </form>

    <form method="post">
            @csrf
            <input type='hidden' name='tok' value = '<?php echo base64_encode(openssl_random_pseudo_bytes(16)); ?>' />
            <input type="hidden" name="username" value="stevenH" /><br />
            <input type="hidden" name="password" value="password" /><br />
            <input class="bigbutton w3-card w3-button w3-row w3-white" type="submit" name="submit" value="Log in as Steven (analyst)" formaction="verify"/>
    </form>
</div>
@endsection
