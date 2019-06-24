@php
    //dd($logs);
@endphp
<table class="table">
    <thead class="text-primary">
    <tr>
        <th>
            Действие
        </th>
        <th>
            Пользователь
        </th>
        <th>
            Клиент
        </th>
        <th>
            Время
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        @php
            $user = $log->user()->first();
            $client = $log->client()->first();
        @endphp
        <tr>
            <td>
                {!! $log->name !!}
            </td>
            <td>
                @if ($user->nick)
                    <a href="{{ route('ProfilePage', $user->nick) }}">
                        {{$user->name}}
                    </a>
                @else
                    {{$user->name}}
                @endif
            </td>
            <td>
                <a href="{{ route('CrmPage', $client->id) }}">
                    {{ $client->name }}
                </a>
            </td>
            <td>
                {{ dateFormat($log->created_at) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>