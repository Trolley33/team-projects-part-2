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
</div>
@endsection
