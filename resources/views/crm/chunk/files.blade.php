<div class="card files-container">
    <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">
            <i class="fas fa-file"></i>
        </div>
        {{--<p class="card-category">Used Space</p>--}}
        <h3 class="card-title">
            Прикрепленные файлы
        </h3>
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
                        Размер
                    </th>
                    <th>
                        Действия
                    </th>
                </tr>
                </thead>
                <tbody>
                @if($files)
                    @foreach($files as $item)
                        <tr class="">
                            <td>{{ $item->name }}</td>
                            <td>{{ formatSize($item->size) }}</td>
                            <td class="task-actions"
                                data-url="{{ route('UploadUpdate', $item->id) }}">
                                <div class="icon" data-action="file-rename" title="Переименовать">
                                    <i class="fas fa-pencil-alt"></i>
                                </div>
                                <a class="icon"
                                   title="Скачать"
                                   href="{{ route('Download', $client->id).'?name='.$item->name }}"
                                   target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                <div class="icon" data-action="remove" title="Удалить">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <form action="{{ route('ClientUpload', $client->id) }}" class="upload-form">
                @csrf
                <input type="file" name="files" multiple>
            </form>
        </div>
    </div>

    <div class="card-footer">
        <i class="fas fa-dumbbell"></i> Вес всех файлов: {{ formatSize($fileSizes) }}.
    </div>
</div>
