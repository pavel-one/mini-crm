<div class="chat-top">
    <div class="image">
        <img src="{{ getUserPhoto($from->id) }}" alt="">
    </div>
    @if ($from->nick)
        <div class="name">
            <a href="{{ route('ProfilePage', $from->nick) }}">
                {{ $from->name }}
            </a>
            <span>({{'@'.$from->nick}})</span>
        </div>
    @else
        <div class="name">{{ $from->name }}</div>
    @endif
</div>
<div class="chat-body">
    @foreach($messages as $message)
        @php
            $fromUser = $message->fromUser()->firstOrFail();
        @endphp
        <div class="message"
             data-id="{{ $message->id }}"
                {{--        @if (!$message->read_user)--}}
                {{--            style="background: #eeeeee"--}}
                {{--        @endif--}}
        >
            <div class="message-photo">
                <img src="{{ getUserPhoto($fromUser->id) }}">
            </div>
            <div class="message-body">
                <div class="message-body-top">
                    <div class="message-name">
                        @if ($fromUser->nick)
                            <div class="name">
                                <a href="{{ route('ProfilePage', $fromUser->nick) }}">
                                    {{ $fromUser->name }}
                                </a>
                                <span>({{'@'.$fromUser->nick}})</span>
                            </div>
                        @else
                            <div class="name">{{ $fromUser->name }}</div>
                        @endif
                    </div>
                    <div class="message-date">
                        {{ dateFormat($message->created_at) }}
                    </div>
                </div>
                <div class="message-text">
                    {{ $message->text }}
                    @if ($message->files)
                        @php
                            $files = $message->files;
                            $files = json_decode($files);
                        @endphp
                        <div class="message-text-files">
                            <ol>
                                @foreach ($files as $file)
                                    <li>
                                        <a target="_blank"
                                           href="{{ route('msgDownload', $file->name) }}">
                                            {{ $file->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="chat-footer">
    <form method="post"
          action="{{ route('NewMessageLk', $topic->id) }}"
          data-get="{{ route('LoadTopic', $topic->id) }}"
          data-lk="true">
        @csrf
        <input type="text" autocomplete="off" name="message" placeholder="Введите сообщение">
        <button type="submit">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>