@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Профиль пользователя {{ $user->nick }}</h4>
                    <p class="card-category">Тут будут задачи и пр пр</p>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo" id="user-photo-upload">
                        <img class="img" src="{{ getUserPhoto($user->id) }}">
                    </a>
                </div>
                <div class="card-body">
                    <h6 class="card-category text-gray">{{ $user->sudo ? 'Бог' : 'Разработчик' }}</h6>
                    <h4 class="card-title">{{ $user->name }} <span>({{'@'. $user->nick }})</span></h4>
                    <div class="profile-info">
                        <div class="row-data">
                            <div class="title">Имя</div>
                            <div class="val">{{ $user->name }}</div>
                        </div>
                        @if ($user->phone)
                            <div class="row-data">
                                <div class="title">Телефон</div>
                                <div class="val">{{ $user->phone }}</div>
                            </div>
                        @endif
                        <div class="row-data">
                            <div class="title">Email</div>
                            <div class="val">
                                <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                            </div>
                        </div>
                        <div class="buttons">
                            <div class="btn btn-primary">Написать сообщение</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection