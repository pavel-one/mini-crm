@extends('layouts.template')

@section('content')
    <div class="row servers_controller">
        <div class="col-xs-12 col-md-8">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">
                        <span>Сервера</span>
                    </h4>
                    <div class="card-category">
                        <p>Создание и удаление срверов</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Название
                                </th>
                                <th>
                                    Краткое описание
                                </th>
                                <th>
                                    HOST
                                </th>
                                <th>
                                    Логин
                                </th>
                                <th>
                                    Пароль
                                </th>
                                <th>
                                    Доступы от хостинга
                                </th>
                                <th>
                                    Действия
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                /** @var \App\Server $server */
                            @endphp
                            @foreach($servers as $server_obj)
                                <tr>
                                    <td>
                                        #{{$server_obj->id}}
                                    </td>
                                    <td>
                                        {{ $server_obj->name }}
                                    </td>
                                    <td>
                                        {{$server_obj->description}}
                                    </td>
                                    <td>
                                        {{$server_obj->host}}
                                    </td>
                                    <td>
                                        {{$server_obj->login}}
                                    </td>
                                    <td>
                                        {{$server_obj->password}}
                                    </td>
                                    <td>
                                        @if($server_obj->hosting)
                                            @php
                                                $datas = json_decode($server_obj->hosting, true);
                                            @endphp
                                        <table style="box-shadow: 0px 0px 10px rgba(0,0,0,.05);">
                                            @foreach($datas as $key => $data)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $data }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                    <td class="task-actions" data-url="#">
                                        <div class="icon" data-action="remove">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-primary" id="create-server-btn">
                            Добавить сервер
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="create-server">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('ServerCreate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Добавить сервер</h5>
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
                        <label for="host">Host</label>
                        <input type="text" required class="form-control" name="host" id="host" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" name="login" id="login" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="text" class="form-control" name="password" id="password"
                               aria-describedby="emailHelp"
                               placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="description">Краткое описание</label>
                        <input class="form-control" name="description" id="description"/>
                    </div>
                    <div class="form-group">
                        <label for="description">Доступы от хостинга</label>
                        <table class="json-generator">
                        </table>
                        <button type="button" class="btn btn-primary" id="add_field_generator">Добавить поле</button>
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
