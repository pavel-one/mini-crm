@extends('layouts.template')

@section('content')
    <div class="card-columns">
        @include('crm.chunk.description')
        @include('crm.chunk.tasks')
        @include('crm.chunk.access')
        @include('crm.chunk.chat')


        @include('crm.chunk.files')
        @if ($user->sudo)
            @include('crm.chunk.payments')
        @endif

        <div class="card" id="ScrumTasker">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-history"></i>
                </div>
                {{--<p class="card-category">Задачи</p>--}}
                <h3 class="card-title">
                    SCRUM Таскер
                </h3>
            </div>
            <div class="card-body" id="TaskApp">
                <div class="task-top">
                    <h4>Спринт №1</h4>
                    <div class="times">
                        <div class="start">Начало работы: 21.02</div>
                        <div class="end">Конец работы: 31.02</div>
                    </div>
                </div>
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
                                Дата оплаты
                            </th>
                            <th>
                                Действия
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="task-success-percent" >
                    <div class="process"></div>
                    <div class="success" style="width: 50%"></div>
                    <div class="text" style="left: 49%">50%</div>
                </div>
            </div>
        </div>
    </div>


        <div class="card" id="client_log" data-action="{{route('CrmPageLog', $client->id)}}">
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


@endsection