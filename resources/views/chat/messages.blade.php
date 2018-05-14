@foreach($messages as $message)
  <div class="message">
    <div class="message__info">
      <div style="color: {{ $message->poster->roleColor() }}" class="message__user">
        <a class="link" href="{{ route('profile', ['id' => $message->poster->id]) }}">
          <i class="{{ $message->poster->roleIcon() }}"></i>
          {{ $message->poster->roleName() }}
          {{ $message->poster->username }}
        </a>
      </div>
      @if ($message->poster->image != null)
        <img class="message__avatar" src="{{ url("files/img/{$message->poster->image}") }}"></img>
      @else
        <img class="message__avatar" src="{{ url("img/profile.png") }}"></img>
      @endif

      @if (\App\Policy::isModerator(auth()->user()))
        <div class="message__moderation mbox mbox--mini-bottom mbox--mini--top">
          <a href="{{ route('shout-delete', ['id' => $message->id]) }}">
            <button class="btn">
              <i class="fas fa-eraser"></i>
              Delete
            </button>
          </a>
        </div>
      @endif

      <div class="message__time">{{ $message->created_at }}</div>
    </div>
    <div class="message__text">{!! $message->asHtml() !!}</div>
  </div>
@endforeach