<p>
    Привет, {{ $name }}! Тебе открыт доступ к CRM!
</p>
<table>
    <tr>
        <td>Пароль:</td>
        <td>{{ $realPass }}</td>
    </tr>
    <tr>
        <td>Логин:</td>
        <td>{{ $email }}</td>
    </tr>
</table>
<p>
    После авторизации не забудь заполнить <a href="{{ route('Profile') }}">свой профиль</a>
</p>