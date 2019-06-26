@extends('layouts.template')

@section('content')
    <div class="card-columns">
        <div class="card">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                {{--<p class="card-category">Задачи</p>--}}
                <h3 class="card-title">
                    Ожидает оплату
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table payment-table">
                        <thead class=" text-primary">
                        <tr>
                            <th>
                                Название
                            </th>
                            <th>
                                Цена
                            </th>
                            <th>
                                Проект
                            </th>
                            <th>
                                Действия
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($payments)
                            @foreach($payments as $item)
                                @php
                                    $client = $item->client()->get()->first()
                                @endphp
                                <tr class="{{ $item->active ? 'success' : '' }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ price_format($item->price) }}р.</td>
                                    <td><a href="{{ route('CrmPage', $client->id) }}">{{ $client->name }}</a></td>
                                    <td class="task-actions"
                                        data-url="{{ route('PaymentUpdate', [$client->id, $item->id]) }}">
                                        @if($item->active === 0)
                                            <div class="icon" data-action="success">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        @else
                                            <div class="icon" data-action="refresh">
                                                <i class="fas fa-sync-alt"></i>
                                            </div>
                                            <div class="icon" data-action="remove">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                {{--<p class="card-category">Задачи</p>--}}
                <h3 class="card-title">
                    Экстренные оповещения
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('alert') }}" id="alert_form" method="post">
                    @csrf
                    <input type="text" name="message" placeholder="Срочно! Всем работать!">
                </form>
            </div>
        </div>
        <div class="card reload_works">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3 class="card-title">
                    Кто над чем работает
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table payment-table">
                        <thead class=" text-primary">
                        <tr>
                            <th>
                                Задача
                            </th>
                            <th>
                                Общее время
                            </th>
                            <th>
                                Прошло с запуска
                            </th>
                            <th>
                                Проект
                            </th>
                            <th>
                                Работает
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($Tasks)
                            @foreach($Tasks as $task)
                                @php
                                    $client = $task->client()->first();
                                @endphp
                                <tr>
                                    <td>{!! $task->text !!}</td>
                                    <td>{{ timeFormat($task->time) }}</td>
                                    <td>{{ timeFormat($task->time_tmp) }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('CrmPage', $client->id) }}">
                                            {{$client->name}}
                                        </a>
                                    </td>
                                    @if ($task->user_tmp)
                                        @php
                                            $tmpUser = $task->userTmp()->first();
                                        @endphp
                                        <td>
                                            <a target="_blank" href="{{ route('ProfilePage', $tmpUser->nick) }}">
                                                {{$tmpUser->name}}
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card" id="client_log" data-action="{{route('AboutPageLog', $client->id)}}">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-history"></i>
                </div>
                {{--<p class="card-category">Задачи</p>--}}
                <h3 class="card-title">
                    Лог действий
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                </div>
            </div>
            <div class="card-footer">
                <i class="fas fa-dollar-sign"></i> Последние 100 действий
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            setInterval(function () {
                $('.reload_works').reloadObj();
            }, 2000);
        })
    </script>
@endsection