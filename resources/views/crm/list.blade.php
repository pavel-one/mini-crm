@extends('layouts.template')

@section('content')
    <div class="row">
        @if ($user->sudo)
            <div class="col-xs-12 col-md-3">
                <div class="card cart-create">
                    {{--                    <img src="..." class="card-img-top" alt="...">--}}
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-plus"></i></div>
                    </div>
                </div>
            </div>
        @endif

        @if (count($clients) > 0)
            @foreach($clients as $client)
                @php
                    $percent = $client->getPercent();
                @endphp
                <div class="col-xs-12 col-md-3 listing-clients">
                    <div class="card">
                        @if ($client->photo)
                            <div class="over">
                                <img src="{{asset('storage/'. $client->photo)}}" alt="">
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $client->name }}
                                <span class="link">
                                    <a target="_blank" href="{{ $client->url }}">({{ $client->url }})</a>
                                </span>
                                @if ($client->active)
                                    <span class="badge badge-primary">В разработке</span>
                                @endif
                            </h5>
                            <div class="card-info">

                            </div>
                            <p class="card-text">
                                {{ $client->description }}
                            </p>
                            <a href="{{ route('CrmPage', $client->id) }}" class="btn btn-primary">Подробнее</a>
                            {{--                            <a href="{{ route('CrmPage', $client->id) }}" class="btn btn-secondary">В проект</a>--}}
                        </div>
                        @if ($percent['percent'])
                            <div class="card_success_container">
                                @if ($percent['percent'] != 100)
                                    <div class="success" style="width: {{$percent['percent']}}%"></div>
                                    <div class="text" style="left: {{$percent['percent'] - 2}}%">{{$percent['percent']}}
                                        %
                                    </div>
                                @else
                                    <div class="success" style="width: 100%; background: #4caf50"></div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        @else
            <p>Нет текущих клиентов</p>
        @endif
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="create-crm">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('crm') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Создать клиента</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" required class="form-control" name="name" id="name"
                               aria-describedby="emailHelp" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="url">Url</label>
                        <input type="text" required class="form-control" name="url" id="url" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                               placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="description">Краткое описание</label>
                        <input class="form-control" name="description" id="description"/>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection