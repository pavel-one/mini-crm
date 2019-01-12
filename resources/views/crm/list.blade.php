@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <div class="card cart-create">
                {{--<img src="..." class="card-img-top" alt="...">--}}
                <div class="card-body">
                    <div class="icon"><i class="fas fa-plus"></i></div>
                </div>
            </div>
        </div>
        @if (count($clients) > 0)
            @foreach($clients as $client)
                <div class="col-xs-12 col-md-3">
                    <div class="card">
                        {{--<img src="..." class="card-img-top" alt="...">--}}
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $client->name }}
                                @if ($client->active)
                                    <span class="badge badge-primary">В разработке</span>
                                @endif
                            </h5>
                            <p class="card-text">
                                {{ $client->description }}
                            </p>
                            <a href="{{ route('CrmPage', $client->id) }}" class="btn btn-primary">Подробнее</a>
                            <a href="{{ route('CrmPage', $client->id) }}" class="btn btn-secondary">В проект</a>
                        </div>
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
                        <input type="text" required class="form-control" name="name" id="name" aria-describedby="emailHelp" placeholder="">
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
                        <input type="text" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="description">Краткое описание</label>
                        <input class="form-control" name="description" id="description" />
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