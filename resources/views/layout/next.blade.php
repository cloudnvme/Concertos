<!DOCTYPE html>
<html>
  <head>
    @section('title')
      <title>Concertos</title>
    @show
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="{{ url('css/vendor/toastr.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('css/main/next.css') }}"/>
    <script src="{{ url('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>
    @yield('head-bottom')
  </head>

  <body>
    <div class="grid-wrapper">
      <a href="{{ route('home') }}" class="logo">
        <i class="fa fa-music"></i>
        <span class="logo__symbol">Concertos</span>
      </a>
      <div class="nav">
        <a href="{{ route('home') }}" class="nav__link">Home</a>
        <a href="{{ route('profile', ['id' => auth()->user()->id]) }}" class="nav__link">Profile</a>
        <a href="{{ route('inbox', ['id' => auth()->user()->id]) }}" class="nav__link">Inbox</a>
        <a href="{{ route('torrents') }}" class="nav__link">Torrents</a>
        <a href="{{ route('requests') }}" class="nav__link">Requests</a>
        <a href="{{ route('forum_index') }}" class="nav__link">Forums</a>
        <a href="{{ route('upload') }}" class="nav__link">Upload</a>
        <a href="{{ route('bookmarks') }}" class="nav__link">Bookmarks</a>
        @if (\App\Policy::isModerator(auth()->user()))
          <a href="{{ route('staff_dashboard') }}" class="nav__link">Moderation</a>
        @endif
      </div>
      @if (auth()->user()->unreadCount() == 1)
        <div class="info-bar info-bar--visible">
          <a href="{{ route('inbox', ['id' => auth()->user()->id]) }}" class="nav__link">You have 1 unread notification</a>
        </div>
      @elseif (auth()->user()->unreadCount() > 1)
        <div class="info-bar info-bar--visible">
          <a href="{{ route('inbox', ['id' => auth()->user()->id]) }}" class="nav__link">You have {{ auth()->user()->unreadCount() }} unread notifications</a>
        </div>
      @elseif (auth()->user()->unmoderatedCount() == 1)
        <div class="info-bar info-bar--visible">
          <a href="{{ route('moderation') }}" class="nav__link">You need to moderate {{ auth()->user()->unmoderatedCount() }} torrent</a>
        </div>
      @elseif (auth()->user()->unmoderatedCount() > 1)
        <div class="info-bar info-bar--visible">
          <a href="{{ route('moderation') }}" class="nav__link">You need to moderate {{ auth()->user()->unmoderatedCount() }} torrents</a>
        </div>
      @else
        <div clas="info-bar"></div>
      @endif

      <div class="user-info">
        {{ auth()->user()->username  }}
        <div class="user-info__content">
          <span class="user-info__item">
            <i class="{{ auth()->user()->roleIcon() }}"></i>
            {{ auth()->user()->roleName() }} {{ auth()->user()->username  }}</span>
          <span class="user-info__item">
            <i class="fa fa-download"></i>
            Download: {{ auth()->user()->getDownloaded() }}
          </span>
          <span class="user-info__item">
            <i class="fa fa-upload"></i>
            Upload: {{ auth()->user()->getUploaded() }}
          </span>
          <span class="user-info__item">
            <i class="fa fa-signal"></i>
            Buffer: {{ auth()->user()->untilRatio() }}
          </span>
          <span class="user-info__item">
            <i class="fas fa-percent"></i>
            Ratio: {{ auth()->user()->getRatioString() }}
          </span>
        </div>
      </div>
      <div class="content">
        @yield('content')
      </div>
      <div class="footer">
        <div class="section">
          <a href="{{ route('home') }}" class="section__title section__title--main">
            <i class="fa fa-music"></i>
            Concertos
          </a>
          <span class="section__item">Tracker with Strict Quality Control for Live Concerts</span>
        </div>

        <div class="section">
          <span class="section__title">Account</span>
          <a href="{{ route('profile', ['id' => auth()->user()->id ]) }}" class="section__link">My Profile</a>
        </div>

        <div class="section">
          <span class="section__title">Community</span>
          <a href="{{ route('forum_index') }}" class="section__link">Forums</a>
          <a href="{{ route('members') }}" class="section__link">Members</a>
          <a href="{{ route('articles') }}" class="section__link">News</a>
        </div>

        <div class="section">
          <span class="section__title">Pages</span>
          <a href="{{ route('page', ['id' => 1]) }}" class="section__link">Rules</a>
          <a href="{{ route('page', ['id' => 3]) }}" class="section__link">FAQ</a>
          <a href="{{ route('blacklist') }}" class="section__link">Blacklist</a>
        </div>

        <div class="section">
          <span class="section__title">Info</span>
          <a href="{{ route('staff') }}" class="section__link">Staff</a>
          <a href="{{ route('internal') }}" class="section__link">Internals</a>
          <a href="{{ route('page', ['id' => 7]) }}" class="section__link">Terms of Use</a>
        </div>
      </div>

      @yield('javascripts')
    </div>

    {!! Toastr::message()  !!}
  </body>
</html>