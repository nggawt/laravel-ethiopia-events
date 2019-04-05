@component('mail::message')
# Introduction

The body of your message.
	@if(count($eventUser))
		@foreach ($eventUser as $key => $value)
	    	<p>This is events {{ $key }}: {{ $value }}</p>
		@endforeach
	@endif
	
@component('mail::button', ['url' => ''])
	Button Text
@endcomponent

	Thanks,<br>
	{{ config('app.name') }}
@endcomponent
