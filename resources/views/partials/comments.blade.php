@foreach ($comments as $comment)
  <div class="message">
    <div class="message__info">
      <div  class="message__user">
        @if($comment->anon)
          <i class="fas fa-question-circle"></i>
          <span class="text-bold">Anonymous</span>
        @else
          {!! $comment->user->getColoredFullName() !!}
        @endif
      </div>
      @if ($comment->user->image != null || $comment->anon)
        <img class="message__avatar" src="{{ url("files/img/{$comment->user->image}") }}"></img>
      @else
        <img class="message__avatar" src="{{ url("img/profile.png") }}"></img>
      @endif

      @if (\App\Policy::isModerator(auth()->user()))
        <div class="message__moderation mbox mbox--mini-bottom mbox--mini--top">
          <a href="{{ route('comment_delete', ['id' => $comment->id]) }}">
            <button class="btn">
              <i class="fas fa-eraser"></i>
              Delete
            </button>
          </a>
        </div>
      @endif

      <div class="message__time">{{ $comment->created_at }}</div>
    </div>
    <div class="message__text">@emojione($comment->getContentHtml())</div>
  </div>
@endforeach