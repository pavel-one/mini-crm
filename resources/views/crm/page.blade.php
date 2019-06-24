@extends('layouts.template')

@section('content')
    <div class="row client-editable"
         data-action="{{ route('CrmPageUpdate', $client->id) }}"
         data-access-action="{{ route('AccessUpdate', $client->id) }}">
        @include('crm.chunk.description')
        @include('crm.chunk.access')
    </div>
    <div class="row">
        @include('crm.chunk.chat')
        @include('crm.chunk.tasks')
    </div>
    <div class="row">
        @include('crm.chunk.files')
        @if ($user->sudo)
            @include('crm.chunk.payments')
        @endif
    </div>
    <div class="row" id="client_log" data-action="{{route('CrmPageLog', $client->id)}}">
        <div class="col-xs-12 col-md-6">
            <div class="card">
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
    </div>


@endsection