@extends('layouts.template')

@section('content')
    <div class="row users_controller">
        <div class="col-xs-12 col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">
                        <span>Пользователи</span>
                    </h4>
                    <div class="card-category">
                        <p>Создание и удаление пользователей</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                            <tr>
                                <th>
                                    Фото
                                </th>
                                <th>
                                    Имя
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Телефон
                                </th>
                                <th>
                                    Админ
                                </th>
                                <th>
                                    Действия
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="user_photo">
                                            <img src="{{ getUserPhoto($user->id) }}">
                                        </div>
                                    </td>
                                    <td class="user_name">
                                        <a href="#">{{ $user->name }}</a>
                                        @if ($user->nick)
                                            <span>({{'@'.$user->nick}})</span>
                                        @endif
                                    </td>
                                    <td class="user_email">
                                        {{ $user->email }}
                                    </td>
                                    <td class="user_phone">
                                        {{ $user->phone }}
                                    </td>
                                    <td class="user_admin">
                                        @if ($user->sudo)
                                            <span style="color: green">Да</span>
                                        @else
                                            <span style="color: red">Нет</span>
                                        @endif
                                    </td>
                                    <td class="task-actions" data-url="{{ route('UserRemove', $user->id) }}">
                                        <div class="icon" data-action="remove">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-primary"
                                id="create_user"
                                data-action="{{ route('UserCreate') }}">
                            Создать пользователя
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection