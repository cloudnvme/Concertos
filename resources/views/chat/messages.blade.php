@foreach($messages as $message)
  <div class="chat-message">
    <div class="chat-message__info">
      <div style="color: {{ $message->poster->roleColor() }}" class="chat-message__poster">
        <a class="link" href="{{ route('profile', ['id' => $message->poster->id]) }}">
          <i class="{{ $message->poster->roleIcon() }}"></i>
          {{ $message->poster->roleName() }}
          {{ $message->poster->username }}
        </a>
      </div>
      @if ($message->poster->image != null)
        <img class="chat-message__avatar" src="{{ url("files/img/{$message->poster->image}") }}"></img>
      @else
        <img class="chat-message__avatar" src="{{ url("img/profile.png") }}"></img>
      @endif
      <div class="chat-message__time">{{ $message->created_at }}</div>
    </div>
    <div class="chat-message__text">{!! $message->asHtml() !!}</div>
  </div>
@endforeach