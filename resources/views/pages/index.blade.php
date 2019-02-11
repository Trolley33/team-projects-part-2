@extends('layouts.app')

@section('content')
<div class="center-box w3-container w3-center">
    <div class="column">
        <a href="login"><button class="index-button login-button w3-button"
            style="background-image: url({{asset('images/Team-Login.png')}});"></button></a><br />
    </div>
    <div class="column">
        <a href="FAQ"><button class="index-button faq-button w3-button"
            style="background-image: url({{asset('images/Team-FAQ.png')}});"></button></a>
    </div>

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
