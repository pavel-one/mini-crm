<div class="card client-editable" data-action="{{ route('CrmPageUpdate', $client->id) }}" data-access-action="{{ route('AccessUpdate', $client->id) }}">
    <div class="card-header card-header-primary">
        <h4 class="card-title">
            Доступы
        </h4>
        <p class="card-category">
            Доступы от проекта предоставленные клиентом
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
                @if($access)
                    @foreach($access as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                            <span class="accessClass" data-name="{{ $item->name }}"
                                                  data-id="{{ $item->id }}">
                                                {{ $item->value }}
                                            </span>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary createAccess" style="width: 99%">
                <i class="fas fa-plus"></i> Добавить доступ
            </button>
        </div>
    </div>
</div>
