<!-- Template to display error/success messages under navbar if they are set in the session -->

<div class="w3-center" id='messages'>
@if (count($errors) > 0)
	@foreach ($errors->all() as $error)
		<div class='w3-red' id='errors' style="width: 100%;">
			{{$error}}
		</div>
	@endforeach
@endif

@if (session('success'))
	<div class='w3-green' id='success' style="width: 100%;">
		{{session('success')}}
	</div>
@endif

@if (session('error'))
	<div class='w3-red' id='error' style="width: 100%;">
		{{session('error')}}
	</div>
@endif
</div>