@extends('layout.next')

@section('title')
  <title>{{ $topic->name }} - Forums - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')
  <link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
    </a>
  </li>
  <li>
    <a href="{{ route('forum_display', array('id' => $forum->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
    </a>
  </li>
  <li>
    <a href="{{ route('forum_topic', array('id' => $topic->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
    </a>
  </li>
@endsection

@section('content')
  <div class="pbox pbox--small-bottom">
    @if(auth()->check() && (\App\Policy::isModerator(auth()->user()) || $topic->user_id == auth()->user()->id))
      @if($topic->state == "close")
        <a href="{{ route('forum_open', ['id' => $topic->id, ])}}" class="btn mbox mbox--mini-right">
          {{ trans('forum.open-topic') }}
        </a>
      @else
        <a href="{{ route('forum_close', ['id' => $topic->id, ])}}" class="btn mbox mbox--mini-right">
          {{ trans('forum.mark-as-resolved') }}
        </a>
      @endif
    @endif

    @if(auth()->check() && \App\Policy::isModerator(auth()->user()))
      <a href="{{ route('forum_edit_topic', ['id' => $topic->id]) }}" class="btn mbox mbox--mini-right">
        {{ trans('forum.edit-topic') }}
      </a>
      <a href="{{ route('forum_delete_topic', ['id' => $topic->id]) }}" class="btn mbox mbox--mini-right">
        {{ trans('forum.delete-topic') }}
      </a>
    @endif

    @if(auth()->check() && \App\Policy::isModerator(auth()->user()))
      @if($topic->pinned == 0)
        <a href="{{ route('forum_pin_topic', ['id' => $topic->id]) }}"
           class="btn mbox mbox--mini-right">{{ trans('forum.pin') }} {{ strtolower(trans('forum.topic')) }}</a>
      @else
        <a href="{{ route('forum_unpin_topic', ['id' => $topic->id]) }}"
           class="btn mbox mbox--mini-right">{{ trans('forum.unpin') }} {{ strtolower(trans('forum.topic')) }}</a>
      @endif
    @endif
  </div>


  <div class="block mbox mbox--bottom">
    <div class="block__title">Topic - {{ $topic->name }}</div>
    <div class="block__content">
      @foreach ($posts as $k => $p)
        <div class="post mbox mbox--small-bottom" id="post-{{ $p->id }}">
          <div class="post__header flex flex__centered pbox pbox--mini">
            <div class="mbox mbox--small-right">
              {{ date('M d Y', $p->created_at->getTimestamp()) }} ({{ $p->created_at->diffForHumans() }})
            </div>
            <a class="text-bold permalink"
               href="{{ route('forum_topic', array('id' => $p->topic->id)) }}?page={{$p->getPageNumber()}}#post-{{$p->id}}">Permalink</a>
          </div>

          <div class="flex">
            <div class="post__info flex flex__centered flex--column pbox pbox--small">
              @if($p->user->image != null)
                <img class="post__avatar" src="{{ url('files/img/' . $p->user->image) }}"/>
              @else
                <img class="post__avatar" src="{{ url('img/profile.png') }}"/>
              @endif
              <div class="mbox mbox--small-top">{!! $p->user->getFullName() !!}</div>
              <div class="post__user-title">{{ $p->user->title }}</div>
              <p>{{ trans('user.member-since') }}: {{ date('M d Y', $p->user->created_at->getTimestamp()) }}</p>
            </div>

            <div class="post__message flex__expanded pbox pbox--mini">{!! $p->getContentHtml() !!}</div>
          </div>
        </div>
      @endforeach
      {{ $posts->links() }}
    </div>
  </div>

  @if ($topic->state != 'close' || \App\Policy::isModerator(auth()->user()))
  <div class="block">
    <div class="block__title">Reply</div>
    <div class="block__content">
      <form role="form" method="POST" action="{{ route('forum_reply',['id' => $topic->id]) }}">
        {{ csrf_field() }}
        @if ($topic->state == 'close' && \App\Policy::isModerator(auth()->user()))
          <div class="text text--danger">This topic is closed, but you can still reply due to you
            being {{auth()->user()->roleName()}}.
          </div>
        @endif
        <textarea class="textarea textarea--vertical" name="content" id="topic-response" cols="30" rows="10"></textarea>
        <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
      </form>
    </div>
  </div>
  @endif
@endsection