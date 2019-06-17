@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Редактировать профиль</h4>
                    <p class="card-category">Заполни свой профиль</p>
                </div>
                <div class="card-body">
                    <form id="edit-profile" data-action="{{ route('Profile') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">Имя</label>
                                    <input name="name" type="text" class="form-control" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">Email</label>
                                    <input name="email" value="{{ $user->email }}" type="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">Телефон</label>
                                    <input name="phone" value="{{ $user->phone }}" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">Никнейм (для упоминания в чате)</label>
                                    <input name="nick" value="{{ $user->nick }}" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-floating">Пароль</label>
                                    <input name="password" type="password" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">Сохранить изменения</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo" id="user-photo-upload">
                        <img class="img" src="{{ getUserPhoto($user->id) }}">
                    </a>
                    <input type="file" name="photo" hidden data-action="{{ route('UploadPhoto') }}">
                </div>
                <div class="card-body">
                    <h6 class="card-category text-gray">{{ $user->sudo ? 'Бог' : 'Разработчик' }}</h6>
                    <h4 class="card-title">{{ $user->name }}</h4>
                    @if ($user->nick)
                        <a href="{{ route('ProfilePage', $user->nick) }}" class="btn btn-primary">Посмотреть профиль</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 vk-chat">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Мои сообщения</h4>
                <p class="card-category">Отвечай, ругайся, подчиняйся</p>
            </div>
            <div class="card-body messages-block">
                <div class="topic-container">
                    @if ($topics)
                        @foreach ($topics as $topic)
                            @php
                                $user_from = $topic->fromUser()->first();
                            @endphp
                            <div class="topic" data-url="{{ route('LoadTopic', $topic->id) }}"
                                 data-topic="{{$topic->id}}">
                                <div class="photo">
                                    <img src="{{ getUserPhoto($user_from->id) }}" alt="">
                                </div>
                                <div class="topic-body">
                                    <div class="name">
                                        {{ $user_from->name }}
                                    </div>
                                    <div class="dsc">
                                        {{ $user->unreadMessages($user_from->id)->count() }} непрочитанных
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="reload-chat" style="width: 869px" id="load-lk-chat">

                </div>
            </div>
        </div>
    </div>
@endsection