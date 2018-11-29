<div class="w3-center">
@if (count($errors) > 0)
	@foreach ($errors->all() as $error)
		<div class='w3-red' style="width: 100%;">
			{{$error}}
		</div>
	@endforeach
@endif

@if (session('success'))
	<div class='w3-green' style="width: 100%;">
		{{session('success')}}
	</div>
@endif

@if (session('error'))
	<div class='w3-red' style="width: 100%;">
		{{session('error')}}
	</div>
@endif
</div>