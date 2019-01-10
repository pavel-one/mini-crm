<div class="col-xs-12 col-md-6">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title">
                <span data-name="name">{{ $client->name }}</span>
                @if ($client->active)
                    <span class="badge badge-primary">В разработке</span>
                @endif
            </h4>
            <div class="card-category">
                <p data-name="description">{{ $client->description ?: 'Нет описания' }}</p>
            </div>
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
                        <td>Телефон:</td>
                        <td>
                            <span data-name="phone">{{ $client->phone }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                                    <span data-name="email"><a
                                                href="mailto:{{ $client->email }}">{{ $client->email }}</a></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Url</td>
                        <td>
                                    <span data-name="url">
                                        <a target="_blank" href="{{ $client->url }}">{{ $client->url }}</a>
                                    </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <form action="{{ route('DeleteClient', $client->id) }}" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger" style="width: 99%">
                        <i class="fas fa-trash"></i> Удалить клиента
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>