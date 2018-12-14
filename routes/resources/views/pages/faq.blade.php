@extends('layouts.app')

@section('content')
<div class="center-box w3-container w3-center">
    <div class="w3-center w3-padding menu w3-white" style='text-align:center;'>
        <h3>Frequently Asked Questions</h3><hr />
        <h4 class = "faq-h4">Topic #1</h4>
        <div class="slideable">
        <p>Lorem ipsum topic test</p>
        </div>
        <h4 class = "faq-h4">Topic #2</h4>
        <div class="slideable">
        <p>Lorem ipsum topic test</p>
        </div>
        <h4 class = "faq-h4">Topic #3</h4>
        <div class="slideable">
        <p>Lorem ipsum topic test</p>
        </div>
        <h4 class = "faq-h4">Topic #4</h4>
        <div class="slideable">
        <p>Lorem ipsum topic test</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('h4').next('.slideable').slideUp(0);
    $('h4').click(function(){
        $(this).next('.slideable').slideToggle();
    })
});
</script>
@endsection
