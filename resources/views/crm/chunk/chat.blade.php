<div class="card">
    <div class="card-header card-header-primary card-header-icon">
        <div class="card-icon">
            <i class="fas fa-comments"></i>
        </div>
        {{--<p class="card-category">Used Space</p>--}}
        <h3 class="card-title">
            Чат по клиенту
        </h3>
    </div>
    <div class="card-body">
        <div class="chat-body" id="chat">
            <div class="reload-chat">
                @if ($messagess)
                    @foreach($messagess as $item)
                        @php
                            $userMsg = $item->user()->get()[0];
                        @endphp
                        <div class="message" data-id="{{ $item->id }}">
                            <div class="message-photo">
                                <img src="{{ getUserPhoto($item->user_id) }}" alt="">
                            </div>
                            <div class="message-body">
                                <div class="message-body-top">
                                    <div class="message-name">
                                        @if ($userMsg->nick)
                                            <a target="_blank"
                                               href="{{ route('ProfilePage', $userMsg->nick) }}">{{ $userMsg->name }}</a>
                                            <span>({{'@' . $userMsg->nick }})</span>
                                        @else
                                            {{ $userMsg->name }}
                                        @endif
                                    </div>
                                    <div class="message-date">
                                        {{ dateFormat($item->created_at) }}
                                    </div>
                                </div>
                                <div class="message-text">
                                    {!! $item->message !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="chat-footer">
            <form method="post" action="{{ route('NewMessage', $client->id) }}"
                  data-get="{{ route('GetAll', $client->id) }}">
                @csrf
                <input type="text" autocomplete="off" name="message" placeholder="Введите сообщение">
                <button type="submit">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="card-footer">
        @if($messagess)
            <i class="fas fa-dumbbell"></i> Количество сообщений: {{ count($messagess) }}.
        @endif
    </div>
</div>
