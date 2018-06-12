@extends('layout.next')

@section('title')
    <title>{{ trans('user.invites') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('invite') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.invites') }}</span>
    </a>
</li>
@endsection

@section('content')
  <div class="block">
    <div class="block__title">Invite a User</div>
    <div class="block__content">
      <p>{{ trans('user.invites-count', ['count' => $user->invites]) }}</p>
      <form action="{{ route('invite') }}" method="post">
        {{ csrf_field() }}
        <div class="flex mbox mbox--small-bottom">
          <span for="email" class="badge col col--small badge--centered mbox mbox--mini-right">
            <i class="far fa-envelope mbox mbox--mini-right"></i>
            {{ trans('common.email') }}
          </span>
          <input class="flex__expanded" name="email" type="email" id="email" size="10" required>
        </div>

        <h3>{{ trans('common.message') }}</h3>
        <textarea class="textarea textarea--vertical" name="message" cols="50" rows="10" id="message"></textarea>
        <button type="submit" class="btn">
          <i class="far fa-paper-plane"></i>
          {{ trans('common.submit') }}
        </button>
      </form>
    </div>
  </div>
@endsection