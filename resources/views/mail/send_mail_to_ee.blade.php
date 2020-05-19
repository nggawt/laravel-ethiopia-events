@component('mail::message')

	@if(count($users))
		@foreach ($users as $key => $value)
	    	<p>{{ $key }}: {{ $value }}</p>
		@endforeach
	@endif

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
