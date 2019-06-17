@php
 $user = Auth::user();
@endphp
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
                <div class="accordion" id="client-info">
                    <div class="card">
                        <button class="btn btn-collapse btn-link" type="button" data-toggle="collapse"
                                data-target="#all-info" aria-expanded="true" aria-controls="all-info">
                            Общая информация
                        </button>

                        <div id="all-info" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#client-info">
                            <div class="card-body">
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
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <button class="btn btn-collapse collapsed btn-link" type="button" data-toggle="collapse"
                                data-target="#dop-info" aria-expanded="false" aria-controls="dop-info">
                            Дополнительная информация
                        </button>
                        <div id="dop-info" class="collapse" aria-labelledby="dop-info" data-parent="#client-info">
                            <div class="card-body">
                                <div style="display: none" id="toolbar">
                  <span class="ql-formats">
                    <select class="ql-header">
                      <option value="1">Heading</option>
                      <option value="2">Subheading</option>
                      <option selected>Normal</option>
                    </select>
                  </span>
                                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-strike"></button>
                    </span>
                                    <span class="ql-formats">
                        <select class="ql-color"></select>
                        <select class="ql-background"></select>
                    </span>
                                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                        <select class="ql-align">
                          <option selected></option>
                          <option value="center"></option>
                          <option value="right"></option>
                          <option value="justify"></option>
                        </select>
                    </span>
                                    <span class="ql-formats">
                        <button class="ql-blockquote"></button>
                        <button class="ql-link"></button>
                        <button class="ql-image"></button>
                        <button class="ql-code-block"></button>
                        <button class="ql-video"></button>
                    </span>
                                    <span class="ql-formats">
                        <button class="ql-clean"></button>
                    </span>
                                </div>
                                <div id="editor" title="Редактировать">
                                    @if($client->full_description)
                                        {!! $client->full_description !!}
                                    @else
                                        <p>Дополнительной информации пока что нет</p>
                                    @endif
                                </div>
                                <div class="btn btn-primary save-editor" style="display: none">Сохранить</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($user->sudo)
                    <form action="{{ route('DeleteClient', $client->id) }}" method="post">
                        @csrf
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger" style="width: 99%">
                            <i class="fas fa-trash"></i> Удалить клиента
                        </button>
                    </form>
                    <div class="client-actions">
                        <div class="btn btn-primary">
                            Установить дату дедлайна
                        </div>
                        @if ($client->active)
                            <div class="btn btn-danger">
                                Снять с разработки
                            </div>
                        @else
                            <div class="btn btn-success">
                                Установить на разработку
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="description-crm">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать описание</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>