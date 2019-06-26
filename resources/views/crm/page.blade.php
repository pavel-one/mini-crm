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