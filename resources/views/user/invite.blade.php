@extends('layout.default')

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
  <div class="container">
    <div class="block compose-invite">
      <h1>Invite a User</h1>
      <p>{{ trans('user.invites-count', ['count' => $user->invites]) }}</p>
      <form action="{{ route('invite') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="email" class="col-sm-2 label label-default">{{ trans('common.email') }}</label>
          <input class="bar" name="email" type="email" id="email" size="10" required>
        </div>

        <h3>{{ trans('common.message') }}</h3>
        <textarea class="bar" name="message" cols="50" rows="10" id="message"></textarea>

        <button type="submit" class="v-button">{{ trans('common.submit') }}</button>
      </form>
    </div>
  </div>
@endsection