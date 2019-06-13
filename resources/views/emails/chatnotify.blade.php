Привет, {{ $user->name }}! Тебя упомянули в проекте: <a href="{{ route('CrmPage', $client->id) }}">{{ route('CrmPage', $client->id) }}</a>.
<br>
<br>
<b>Сообщение:</b> <br>
{{ $msg }}