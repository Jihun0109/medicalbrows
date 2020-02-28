@component('mail::message')
@if (! empty($name))
# {{ $name }} 様
@else
# お客様
@endif

@if (! empty($body))
 {{ $body }}
@else

@endif

ありがとうございました,<br>
{{ config('app.name') }}
@endcomponent
