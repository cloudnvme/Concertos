@extends('layout.next')

@section('head-bottom')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @include('partials.bbcode')
@endsection

@section('content')
  <div class="block block--fixed">
    <div class="block__title">Chatbox</div>
    <div class="block__content">
      <div id="chat" data-last="{{ $lastMessage->id ?? "" }}" class="scrollable-y">
        {!! \App\Http\Controllers\ShoutboxController::renderMessages($shoutboxMessages) !!}
      </div>
    </div>
    <div class="block__footer">
      <div id="chat-error"></div>
      <textarea id="chat-message" class="textarea textarea--vertical bbcode-editor" rows="10"></textarea>
      <button id="send-message" class="btn">
        <i class="far fa-paper-plane"></i>
        Send
      </button>
    </div>
  </div>
  <div class="v-sep"></div>

  @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
  <div class="block">
    <div class="block__title">Featured Torrents</div>
    <div class="block__content">
      <table class="table table--bordered">
        <thead>
        <tr>
          <th>Name</th>
          <th>Size</th>
          <th>{{ trans('torrent.short-seeds') }} </th>
          <th>{{ trans('torrent.short-leechs') }} </th>
          <th>{{ trans('torrent.short-completed') }} </th>
          <th>Featured By</th>
        </tr>
        </thead>
        <tbody>
        @foreach($featured as $key => $f)
          <tr>
            <td><a class="link" href="{{ route('torrent', ['id' => $f->torrent->id]) }}">{{ $f->torrent->name }}</a>
            </td>
            <td>{{ $f->torrent->getSize() }}</td>
            <td>{{ $f->torrent->seeders }}</td>
            <td>{{ $f->torrent->leechers }}</td>
            <td>{{ $f->torrent->times_completed }}</td>
            <td>
              <a href="{{ route('profile', ['id' => $f->user->id]) }}">
                <i class="{{ $f->user->roleIcon() }}"></i>
                {{ $f->user->roleName() }}
                {{ $f->user->username }}
              </a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
  <div class="v-sep"></div>

  <div class="block">
    <div class="block__title">Latest Posts</div>
    <div class="block__content">
      <table class="table table--bordered latest-posts">
        <thead>
        <tr>
          <th class="latest-posts__header-post">{{ trans('forum.post') }}</th>
          <th>{{ trans('forum.topic') }}</th>
          <th>{{ trans('forum.author') }}</th>
          <th>{{ trans('forum.created') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $p)
          @if ($p->topic->viewable())
            <tr>
              <td>
                <a href="{{ $p->getPermalink() }}">{{ $p->getPreview() }}</a>
              </td>
              <td>{{ $p->topic->name }}</td>
              <td>{{ $p->user->username }}</td>
              <td>{{ $p->updated_at->diffForHumans() }}</td>
            </tr>
          @endif
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="v-sep"></div>

  <div class="block">
    <div class="block__title">Latest Topics</div>
    <div class="block__content">
      <table class="table table--bordered">
        <thead>
        <tr>
          <th class="torrents-filename">{{ trans('forum.topic') }}</th>
          <th>{{ trans('forum.author') }}</th>
          <th>{{ trans('forum.created') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topics as $t)
          @if ($t->viewable())
            <tr class="">
              <td><a href="{{ route('forum_topic', array('id' => $t->id)) }}">{{ $t->name }}</a></td>
              <td>{{ $t->first_post_user_username }}</td>
              <td>{{ $t->created_at->diffForHumans() }}</td>
            </tr>
          @endif
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="v-sep"></div>

  <div class="block">
    <div class="block__title">Users Online</div>
    <div class="block__content">
      @foreach($user as $u)
        @if($u->isOnline())
          @if($u->hidden == 1)
            <span class="badge-user text-orange text-bold no-break" style="margin-bottom: 10px;">
              {{ strtoupper(trans('common.hidden')) }}
              @if(\App\Policy::isModerator(auth()->user()))
                <a href="{{ route('profile', array('id' => $u->id)) }}">
                  ({{ $u->username }}
                  @if($u->countWarnings() > 0)
                    <i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title=""
                       data-original-title="{{ trans('common.active-warning') }}">

                    </i>
                  @endif
                  )
                </a>
              @endif
            </span>
          @else
            <a href="{{ route('profile', array('id' => $u->id)) }}">
              <span class="badge-user text-bold no-break no-break" style="color:{{ $u->roleColor() }}; background-image:{{ $u->roleEffect() }}; margin-bottom: 10px;">
                <i class="{{ $u->roleIcon() }}" data-toggle="tooltip" title="" data-original-title="{{ $u->roleName() }}"></i>
                {{ $u->username }}
                @if($u->countWarnings() > 0)
                  <i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>
                @endif
              </span>
            </a>
          @endif
        @endif
      @endforeach
    </div>
  </div>
  <script type="text/javascript" src="{{ url('js/shout.js?v=05') }}"></script>
@endsection