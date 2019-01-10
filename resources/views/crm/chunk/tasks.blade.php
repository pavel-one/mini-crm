<div class="col-xs-12 col-md-7">
    <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
                <i class="fas fa-list-ol"></i>
            </div>
            {{--<p class="card-category">Задачи</p>--}}
            <h3 class="card-title">
                Задачи
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table task-table">
                    <thead class=" text-primary">
                    <tr>
                        <th>
                            Название
                        </th>
                        <th>
                            Общее время
                        </th>
                        <th>
                            Текущее время
                        </th>
                        <th>
                            Действия
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($tasks)
                        @foreach($tasks as $item)
                            <tr class="{{ $item->active ? 'success' : ''}}">
                                <td>
                                    <b>{!! $item->text !!}</b>
                                    @if(!$item->active)
                                        @if($item->time_tmp)
                                            <span class="badge badge-primary">Запущен</span>
                                        @endif
                                        @if($item->time)
                                            <span class="badge badge-secondary">В процессе</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ timeFormat($item->time) }}
                                </td>
                                <td class="time_tmp">
                                    {{ $item->active ? '-' : timeFormat($item->time_tmp) }}
                                </td>
                                <td class="task-actions"
                                    data-url="{{ route('TaskUpdate', [$client->id, $item->id]) }}"
                                    data-task-id="{{ $item->id }}"
                                >
                                    @if($item->active === 0)
                                        @if($item->time_tmp)
                                            <div class="icon" data-action="pause">
                                                <i class="fas fa-pause"></i>
                                            </div>
                                        @else
                                            <div class="icon" data-action="start">
                                                <i class="fas fa-play"></i>
                                            </div>
                                        @endif

                                    @endif

                                    <div class="icon" data-action="refresh">
                                        <i class="fas fa-sync-alt"></i>
                                    </div>
                                    @if($item->active === 1)
                                        <div class="icon" data-action="remove">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    @endif
                                    @if($item->active === 0)
                                        <div class="icon" data-action="success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary createTask"
                        data-action="{{ route('TaskAction', $client->id) }}" style="width: 99%">
                    <i class="fas fa-plus"></i> Создать задачу
                </button>

            </div>
        </div>
        <div class="card-footer">
            <i class="fas fa-stopwatch"></i> Затрачено времени: {{ timeFormat($allTime) }}.
        </div>
    </div>
</div>