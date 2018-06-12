@extends('layout.next')

@section('title')
  <title>{{ $forum->name }} - {{ trans('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
  <meta name="description" content="{{ trans('forum.display-forum') . $forum->name }}">
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
@endsection

@section('content')
  @if(\App\Policy::canCreateTopic(auth()->user(), $category))
    <a href="{{ route('forum_new_topic', array('id' => $forum->id)) }}">
      <button class="btn mbox mbox--bottom">{{ trans('forum.create-new-topic') }}</button>
    </a>
  @endif

  <div class="block">
    <div class="block__title">Forum - {{ $forum->name }}</div>
    <div class="block__content">
      <table class="table table--bordered">
        <thead>
        <tr>
          <th></th>
          <th>{{ trans('forum.topic') }}</th>
          <th>{{ trans('forum.author') }}</th>
          <th>{{ trans('forum.stats') }}</th>
          <th>{{ trans('forum.last-post-info') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topics as $t)
          <tr>
            @if($t->pinned == 0)
              <td class="f-display-topic-icon"><img src="{{ url('img/f_icon_read.png') }}"></td>
            @else
              <td class="f-display-topic-icon"><span class="text-green"><i class="fa fa-thumb-tack fa-2x"></i></span>
              </td>
            @endif
            <td class="pbox pbox--small-bottom pbox--small-top">
              <strong><a href="{{ route('forum_topic', array('id' => $t->id)) }}">{{ $t->name }}</a></strong>

              <div>
                @if($t->state == "close")
                  <span>Closed</span>
                @endif

                @if($t->approved == "1")
                  <span>Approved</span>
                @endif

                @if($t->denied == "1")
                  <span>Denied</span>
                @endif

                @if($t->solved == "1")
                  <span>Solved</span>
                @endif

                @if($t->invalid == "1")
                  <span>Invalid</span>
                @endif

                @if($t->bug == "1")
                  <span>Bug</span>
                @endif

                @if($t->suggestion == "1")
                  <span>Suggestion</span>
                @endif

                @if($t->implemented == "1")
                  <span>Implemented</span>
                @endif
              </div>
            </td>
            <td class="f-display-topic-started"><a
                href="{{ route('profile', ['id' => $t->first_post_user_id]) }}">{{ $t->first_post_user_username }}</a>
            </td>
            <td class="f-display-topic-stats">
              {{ $t->num_post - 1 }} {{ trans('forum.replies') }} \ {{ $t->views }} {{ trans('forum.views') }}
            </td>
            @php $last_post = DB::table('posts')->where('topic_id', '=', $t->id)->orderBy('id', 'desc')->first(); @endphp
            <td>
              <a href="{{ route('profile', ['id' => $t->last_post_user_id]) }}">{{ $t->last_post_user_username }}</a> on
              <time datetime="{{ date('M d Y', strtotime($last_post->created_at)) }}">
                {{ date('M d Y', strtotime($last_post->created_at)) }}
              </time>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      {{ $topics->links() }}
    </div>
  </div>
@endsection