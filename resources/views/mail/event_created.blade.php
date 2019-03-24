@component('mail::message')
# Introduction

The body of your message.

	@foreach ($eventUser as $key => $value)
    	<p>This is events {{ $key }}: {{ $value }}</p>
	@endforeach
	
@component('mail::button', ['url' => ''])
	Button Text
@endcomponent

	Thanks,<br>
	{{ config('app.name') }}
@endcomponent
