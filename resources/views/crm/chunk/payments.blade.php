<div class="col-xs-12 col-md-5">
    <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            {{--<p class="card-category">Задачи</p>--}}
            <h3 class="card-title">
                Долги и оплаты
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
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($payments)
                        @foreach($payments as $item)
                            <tr class="{{ $item->active ? 'success' : '' }}">
                                <td>{{ $item->name }}</td>
                                <td>{{ price_format($item->price) }}р.</td>
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
                </table>
                <button type="button" class="btn btn-secondary createPayment"
                        data-action="{{ route('PaymentCreate', $client->id) }}" style="width: 99%">
                    <i class="fas fa-plus"></i> Добавить
                </button>
            </div>
        </div>
        <div class="card-footer">
            <i class="fas fa-dollar-sign"></i> Общая стоимость проекта: {{ price_format($clientPrice) }}р.
        </div>
    </div>
</div>
{{--<div class="col-xs-12 col-md-3">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title">
                Комментарии
            </h4>
            <p class="card-category">
                Комментарии к клиенту
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class=" text-primary">
                    <tr>
                        <th>
                            Название
                        </th>
                        <th>
                            Значение
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Тест</td>
                        <td>Тест 2</td>
                    </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary createAccess" style="width: 99%">
                    <i class="fas fa-plus"></i> Добавить
                </button>
            </div>
        </div>
    </div>
</div>--}}