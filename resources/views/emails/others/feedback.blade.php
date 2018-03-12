@component('mail::message')

<h1>Feedback</h1>

Name: {{ $data['name'] }}<br>
Contact: {{ $data['contact'] }}<br>
Msg: {{ $data['msg'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
