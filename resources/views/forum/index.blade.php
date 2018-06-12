@extends('layout.next')

@section('title')
  <title>{{ trans('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
  <meta name="description" content="{{ config('other.title') }} - {{ trans('forum.forums') }}">
@endsection


@section('breadcrumb')
  <li class="active">
    <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
    </a>
  </li>
@endsection

@section('content')
  @foreach($categories as $category)
    @if(\App\Policy::canViewForum(auth()->user(), $category) && $category->getForumsInCategory()->count() > 0)
      <div class="block mbox mbox--mini-bottom">
        <div class="block__title">{{ $category->name }}</div>
        <div class="block__content">

          <table class="table table--bordered">
            <thead class="head">
            <tr>
              <td>{{ strtoupper(trans('forum.name')) }}</td>
              <td>{{ strtoupper(trans('forum.posts')) }}</td>
              <td>{{ strtoupper(trans('forum.topics')) }}</td>
              <td>{{ strtoupper(trans('forum.latest')) }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($category->getForumsInCategory() as $categoryChild)
              <tr>
                <td>
                  <span>
                    <a class="link text-bold" href="{{ route('forum_display', ['id' => $categoryChild->id]) }}">
                      <div>{{ $categoryChild->name }}</div>
                    </a>
                  </span>
                  <span>{{ $categoryChild->description }}</span>
                </td>
                <td>
                  {{ $categoryChild->num_post }}
                </td>
                <td>
                  {{ $categoryChild->num_topic }}
                </td>
                <td class="pbox pbox--small-bottom pbox--small-top">
                  <span>{{ trans('forum.last-message') }} - {{ strtolower(trans('forum.author')) }}
                    <i class="fa fa-user"></i>
                    <a href="{{ route('profile', ['id' => $categoryChild->last_post_user_id]) }}">
                      {{ $categoryChild->last_post_user_username }}
                    </a>
                  </span>
                  <br>
                  <span>{{ trans('forum.topic') }}
                    <i class="fa fa-chevron-right"></i>
                    <a
                      href="{{ route('forum_topic', array('slug' => $categoryChild->last_topic_slug, 'id' => $categoryChild->last_topic_id)) }}"> {{ $categoryChild->last_topic_name }}</a>
                  </span>
                  <br>
                  <span>
                    <i class="fa fa-clock-o"></i>
                    {{ $categoryChild->updated_at->diffForHumans() }}
                  </span>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
  @endforeach
@endsection