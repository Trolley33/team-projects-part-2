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
@endsection
