@extends('layout.next')

@section('title')
  <title>{{ $user->username }} - {{ trans('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('profile', ['id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
  </li>
  <li>
    <a href="{{ route('user_settings', ['id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">Settings</span>
    </a>
  </li>
@endsection

@section('content')
  <div class="block mbox mbox--small-bottom">
    <div class="block__title">General Settings</div>
    <div class="block__content">
      <form class="settings" action="{{ route('user_settings', ['id' => $user->id]) }}" method="post">
        @csrf
        <div>
          <label>
            Language Censor Chat?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->censor == 1) checked @endif name="censor"/>
              <span></span>
            </span>
          </label>
        </div>

        <div>
          <label>
            Hide Chat?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->chat_hidden == 1) checked @endif name="chat_hidden"/>
              <span></span>
            </span>
          </label>
        </div>

        <div>
          <label>
            Hidden From Online Block?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->hidden == 1) checked @endif name="onlinehide"/>
              <span></span>
            </span>
          </label>
        </div>

        <div>
          <label>
            Hidden In Peers/History Table?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->peer_hidden == 1) checked @endif name="peer_hidden"/>
              <span></span>
            </span>
          </label>
        </div>

        <div>
          <label>
            Private Profile?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->private_profile == 1) checked @endif name="private_profile"/>
              <span></span>
            </span>
          </label>
        </div>

        @if(config('auth.TwoStepEnabled') == true)
          <label>
            Use Two Step Auth?
            <span class="v-checkbox">
              <input type="checkbox" @if($user->twostep == 1) checked @endif name="twostep"/>
              <span></span>
            </span>
          </label>
        @endif

        <div class="textbar">
          <label for="custom_css" class="mbox mbox--small-right">External CSS Stylesheet</label>
          <input type="text" class="textbar__input" name="custom_css"/>
        </div>

        <button type="submit" class="btn">Save</button>
      </form>
    </div>
  </div>

  <div class="block mbox mbox--small-bottom">
    <div class="block__title">Reset Password</div>
    <div class="block__content">
      <form class="reset-password" action="{{ route('change_password', ['id' => $user->id]) }}" method="post">
        @csrf
        <div class="textbar mbox mbox--small-bottom">
          <label for="current_password" class="reset-password__col">Current Password</label>
          <input type="password" class="textbar__input" name="current_password"/>
        </div>

        <div class="textbar mbox mbox--small-bottom">
          <label for="new_password" class="reset-password__col">New Password</label>
          <input type="password" class="textbar__input" name="new_password"/>
        </div>

        <div class="textbar">
          <label for="new_password" class="reset-password__col">Confirm New Password</label>
          <input type="password" class="textbar__input" name="new_password_confirmation"/>
        </div>

        <button type="submit" class="btn btn-primary">Make The Switch!</button>
      </form>
    </div>
  </div>

  <div class="block mbox mbox--small-bottom">
    <div class="block__title">Change Email</div>
    <div class="block__content">
      <form class="change-email" action="{{ route('change_email', ['id' => $user->id]) }}" method="post">
        @csrf

        <div>
          <span class="change-email__col">Current Email</span>
          {{ $user->email }}
        </div>

        <div class="textbar mbox mbox--small-bottom">
          <label for="current_password" class="reset-password__col">Current Password</label>
          <input type="password" class="textbar__input" name="current_password"/>
        </div>

        <div class="textbar">
          <label for="new_email" class="change-email__col">New Email</label>
          <input type="text" class="textbar__input" name="new_email"/>
        </div>

        <button type="submit" class="btn btn-primary">Make The Switch!</button>
      </form>
    </div>
  </div>

  <div class="block">
    <div class="block__title">Reset PID</div>
    <div class="block__content">
      <form class="reset-pid" action="{{ route('change_pid', ['id' => $user->id]) }}" method="post">
        @csrf
        <div>
          <span class="reset-pid__col">Current PID:</span>
          {{ $user->passkey }}
        </div>
        <div>
          You will have to re-download all your active torrents, after resetting the PID.
        </div>
        <input class="btn" type="submit" value="Reset PID">
      </form>
    </div>
  </div>
@endsection