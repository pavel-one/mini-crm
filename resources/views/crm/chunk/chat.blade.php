<div class="col-xs-12 col-md-6 chat-container">
    <h2>TEST</h2>
    @if($messagess)
        <ul>
        @foreach($messagess as $item)
            {{ $item->message }}
        @endforeach
        </ul>
    @endif
</div>