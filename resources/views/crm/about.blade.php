@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-7">
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
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection