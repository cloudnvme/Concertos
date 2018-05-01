@extends('layout.next')

@section('title')
  <title>{{ $user->username }} - {{ trans('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
  <meta name="description"
        content="{{ trans('user.profile-desc', ['user' => $user->username, 'title' => config('other.title')]) }}">
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('profile', ['id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
  </li>
@endsection

@section('content')
  <div class="profile-block pbox pbox--small mbox mbox--small-bottom">
    @if($user->image != null)
      <img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}"
           class="profile-block__avatar mbox mbox--small-right">
    @else
      <img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}"
           class="profile-block__avatar mbox mbox--small-right">
    @endif

    <div class="profile-block__info">
      <div class="profile-block__title">{!! $user->fullName() !!}</div>
      <div class="profile-block__age">Member since {{ $user->created_at }}</div>

      @if(auth()->user()->id != $user->id)
        @if(auth()->user()->isFollowing($user->id))
          <a href="{{ route('unfollow', ['user' => $user->id]) }}">
            <button class="btn btn-light">
              <i class="fas fa-user-times"></i>
              Unfollow User
            </button>
          </a>
        @else
          <a href="{{ route('follow', ['user' => $user->id]) }}">
            <button class="btn btn-light">
              <i class="fas fa-user-plus"></i>
              Follow User
            </button>
          </a>
        @endif

        <button class="btn btn-light">
          <i class="fas fa-exclamation-triangle"></i>
          Report User
        </button>
      @endif

      @if(\App\Policy::isModerator(auth()->user()))
        <button class="btn btn-light">
          <i class="fas fa-ban"></i>
          Ban User
        </button>

        <a href="{{ route('user_setting', ['id' => $user->id]) }}">
          <button class="btn btn-light">
            <i class="far fa-edit"></i>
            Edit User
          </button>
        </a>

        <button class="btn btn-light">
          <i class="fas fa-eraser"></i>
          Delete User
        </button>
      @endif
    </div>
  </div>

  @if ($followers->isNotEmpty())
    <div class="block followers mbox mbox--small-bottom">
      <div class="block__title">Followers</div>
      <div class="block__content">
        @foreach($followers as $f)
          @if($f->user->image != null)
            <a href="{{ route('profile', ['id' => $f->user_id]) }}">
              <img src="{{ url('files/img/' . $f->user->image) }}" data-toggle="tooltip"
                   title="{{ $f->user->username }}" height="50px" data-original-title="{{ $f->user->username }}">
            </a>
          @else
            <a href="{{ route('profile', ['id' => $f->user_id]) }}">
              <img src="{{ url('img/profile.png') }}" data-toggle="tooltip" title="{{ $f->user->username }}"
                   height="50px" data-original-title="{{ $f->user->username }}">
            </a>
          @endif
        @endforeach
      </div>
    </div>
  @endif

  <div class="block mbox mbox--small-bottom">
    <div class="block__title">
      Public Info
    </div>
    <div class="block__content">
      <table class="public-info">
        <tbody>
        <tr>
          <td>Name</td>
          <td>{{ $user->username }}</td>
        </tr>

        <tr>
          <td>Group</td>
          <td>{{ $user->roleName() }}</td>
        </tr>

        <tr>
          <td>Total Uploads</td>
          <td>{{ $user->torrents->count() }}</td>
        </tr>

        <tr>
          <td>Total Downloads</td>
          <td>{{ $history->where('actual_downloaded', '>', 0)->count() }}</td>
        </tr>
        <tr>
          <td>Total Seeding</td>
          <td>{{ $user->getSeeding() }}</td>
        </tr>

        <tr>
          <td>Total Leeching</td>
          <td>{{ $user->getLeeching() }}</td>
        </tr>

        <tr>
          <td>Title</td>
          <td>{{ $user->title }}</td>
        </tr>

        <tr>
          <td>About Me</td>
          <td>{{ $user->getAboutHTML() }}</td>
        </tr>
        <tr>
          <td>BONs</td>
          <td>{{ $user->getSeedbonus() }}</td>
        </tr>
        <tr>
          <td>Freeleech Tokens</td>
          <td>{{ $user->fl_tokens }}</td>
        </tr>

        <tr>
          <td>Thanks Received</td>
          <td>{{ $user->thanksReceived->count() }}</td>
        </tr>

        <tr>
          <td>Thanks Given</td>
          <td>{{ $user->thanksGiven->count() }}</td>
        </tr>

        <tr>
          <td>Article Comments Made</td>
          <td>{{ $user->comments()->where('article_id', '>', 0)->count() }}</td>
        </tr>

        <tr>
          <td>Torrent Comments Made</td>
          <td>{{ $user->comments()->where('torrent_id', '>', 0)->count() }}</td>
        </tr>

        <tr>
          <td>Request Comments Made</td>
          <td>{{ $user->comments()->where('requests_id', '>', 0)->count() }}</td>
        </tr>

        <tr>
          <td>Forum Topics Made</td>
          <td>{{ $user->topics->count() }}</td>
        </tr>

        <tr>
          <td>Forum Posts Made</td>
          <td>{{ $user->posts->count() }}</td>
        </tr>

        <tr>
          <td>H&R Count</td>
          <td>{{ $user->hitandruns }}</td>
        </tr>

        <tr>
          <td>Warnings</td>
          <td>
            <span>{{ $warnings->count() }} / {!! config('hitrun.max_warnings') !!}</span>
            @if(auth()->check() && \App\Policy::isModerator(auth()->user()))
              <a href="{{ route('warninglog', ['id' => $user->id]) }}">
                <button class="btn">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ trans('user.warning-log') }}
                </button>
              </a>
            @endif
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  @if (auth()->user()->id == $user->id || \App\Policy::isModerator(auth()->user()))
    <div class="block mbox mbox--small-bottom">
      <div class="block__title">
        Private Info
      </div>

      <div class="block__content">
        <table class="table private-info">
          <tbody>
          <tr>
            <td>PID</td>
            <td>{{ $user->passkey }}</td>
          </tr>

          <tr>
            <td>User ID</td>
            <td>{{ $user->id }}</td>
          </tr>

          <tr>
            <td>Last Login</td>
            <td>
              @if ($user->last_login != null)
                {{ $user->last_login->toDayDateTimeString() }}
                ({{ $user->last_login->diffForHumans() }})
              @else N/A
              @endif
            </td>
          </tr>

          <tr>
            <td>Permissions</td>
            <td>
              @if (\App\Policy::canInvite($user))
                <span>Invite</span>
              @endif

              @if (\App\Policy::canComment($user))
                <span>Comment</span>
              @endif

              @if (\App\Policy::canUpload($user))
                <span>Upload</span>
              @endif

              @if (\App\Policy::canDownload($user))
                <span>Download</span>
              @endif

              @if (\App\Policy::canRequest($user))
                <span>Request</span>
              @endif

              @if (\App\Policy::canChat($user))
                <span>Chat</span>
              @endif

              @if (\App\Policy::isModerator($user))
                <span>Moderator</span>
              @endif

              @if (\App\Policy::isAdministrator($user))
                <span>Administrator</span>
              @endif
            </td>
          </tr>

          <tr>
            <td>Invites</td>
            <td>
              @if (\App\Policy::hasUnlimitedInvites($user))
                <span>∞</span>
                <a href="{{ route('inviteTree', ['id' => $user->id]) }}">
                  <button class="btn">
                    <i class="fas fa-users"></i>
                    {{ trans('user.invite-tree') }}
                  </button>
                </a>
              @else
                <span> {{ $user->invites }}</span>
                <a href="{{ route('inviteTree', ['id' => $user->id]) }}">
                  <button class="btn">
                    <i class="fas fa-users"></i>
                    {{ trans('user.invite-tree') }}
                  </button>
                </a>
              @endif
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="block">
      <div class="block__title">H&Rs</div>
      <div class="block__content">
        <table class="table">
          <thead>
          <th>{{ trans('torrent.torrent') }}</th>
          <th>{{ trans('user.warned-on') }}</th>
          <th>{{ trans('user.expires-on') }}</th>
          <th>{{ trans('user.active') }}</th>
          </thead>
          <tbody>
          @foreach($hitrun as $hr)
            <tr>
              <td>
                <a class="view-torrent" data-id="{{ $hr->torrenttitle->id }}"
                   data-slug="{{ $hr->torrenttitle->slug }}"
                   href="{{ route('torrent', array('id' => $hr->torrenttitle->id)) }}" data-toggle="tooltip"
                   title="" data-original-title="{{ $hr->torrenttitle->name }}">{{ $hr->torrenttitle->name }}</a>
              </td>
              <td>
                {{ $hr->created_at }}
              </td>
              <td>
                {{ $hr->expires_on }}
              </td>
              <td>
                @if($hr->active == 1)
                  <span class='label label-success'>{{ trans('common.yes') }}</span>
                @else
                  <span class='label label-danger'>{{ trans('user.expired') }}</span>
                @endif
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        {{ $hitrun->links() }}
      </div>
    </div>

    <div class="buttons flex flex__centered">
      <a href="{{ route('user_settings', ['id' => $user->id]) }}">
        <button class="btn mbox mbox--mini-right"><span class="fa fa-cog"></span> {{ trans('user.account-settings') }}
        </button>
      </a>
      <a href="{{ route('user_edit_profile', ['id' => $user->id]) }}">
        <button class="btn mbox mbox--mini-right"><span class="fa fa-user"></span> {{ trans('user.edit-profile') }}
        </button>
      </a>
      <a href="{{ route('invite') }}">
        <button class="btn mbox mbox--mini-right"><span class="fa fa-plus"></span> {{ trans('user.invites') }}</button>
      </a>
      <a href="{{ route('user_clients', ['id' => $user->id]) }}">
        <button class="btn mbox mbox--mini-right"><span class="fa fa-server"></span> {{ trans('user.my-seedboxes') }}
        </button>
      </a>

      <a href="{{ route('myuploads', ['id' => $user->id]) }}">
        <button class="btn mbox mbox--mini-right"><span class="fa fa-upload"></span> {{ trans('user.uploads-table') }}
        </button>
      </a>
      <a href="{{ route('myactive', ['id' => $user->id]) }}">
        <button class="btn mbox mbox--mini-right"><span class="far fa-clock"></span> {{ trans('user.active-table') }}
        </button>
      </a>
      <a href="{{ route('myhistory', ['id' => $user->id]) }}">
        <button class="btn"><span class="fa fa-history"></span> {{ trans('user.history-table') }}
        </button>
      </a>
    </div>
  @endif
@endsection

@section('content_x')
  <div class="container">
    @if( $user->private_profile == 1 && auth()->user()->id != $user->id && !\App\Policy::isModerator(auth()->user()) )
      <div class="container">
        <div class="jumbotron shadowed">
          <div class="container">
            <h1 class="mt-5 text-center">
              <i class="fa fa-times text-danger"></i>{{ trans('user.private-profile') }}
            </h1>
            <div class="separator"></div>
            <p class="text-center">{{ trans('user.not-authorized') }}</p>
          </div>
        </div>
      </div>
    @else
      <div class="well">
        <div class="row">
          <div class="col-md-12 profile-footer">
            {{ $user->username }} - {{ trans('user.recent-achievements') }}:
            @foreach($user->unlockedAchievements() as $a)
              <img src="/img/badges/{{ $a->details->name }}.png" data-toggle="tooltip" title="" height="50px"
                   data-original-title="{{ $a->details->name }}">
            @endforeach
            <div class="col-xs-1 matches-won"><i
                class="fa fa-trophy text-success"></i><span>{{ $user->unlockedAchievements()->count() }}</span></div>
          </div>
        </div>
      </div>
      <div class="well">
        <div class="row">
          <div class="col-md-12 profile-footer">
            {{ $user->username }} - {{ trans('user.followers') }}:
            @foreach($followers as $f)
              @if($f->user->image != null)
                <a href="{{ route('profile', ['id' => $f->user_id]) }}">
                  <img src="{{ url('files/img/' . $f->user->image) }}" data-toggle="tooltip"
                       title="{{ $f->user->username }}" height="50px" data-original-title="{{ $f->user->username }}">
                </a>
              @else
                <a href="{{ route('profile', ['id' => $f->user_id]) }}">
                  <img src="{{ url('img/profile.png') }}" data-toggle="tooltip" title="{{ $f->user->username }}"
                       height="50px" data-original-title="{{ $f->user->username }}">
                </a>
              @endif
            @endforeach
            <div class="col-xs-1 matches-won"><i
                class="fa fa-group text-success"></i><span>{{ $followers->count() }}</span></div>
          </div>
        </div>
      </div>
      <div class="block">
        <div class="header gradient blue">
          <div class="inner_content">
            <div class="content">
              <div class="col-md-2">
                @if($user->image != null)
                  <img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}" class="img-circle">
                @else
                  <img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}" class="img-circle">
                @endif
              </div>
              <div class="col-md-10">
                <h2>{{ $user->username }}
                  @if($user->isOnline())
                    <i class="fa fa-circle text-green" data-toggle="tooltip" title=""
                       data-original-title="{{ trans('user.online') }}"></i>
                  @else
                    <i class="fa fa-circle text-red" data-toggle="tooltip" title=""
                       data-original-title="{{ trans('user.offline') }}"></i>
                  @endif
                  @if($user->getWarning() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true"
                                                 data-toggle="tooltip" title=""
                                                 data-original-title="{{ trans('user.active-warning') }}"></i>@endif
                  @if($user->notes->count() > 0 && \App\Policy::isModerator(auth()->user()))<i
                    class="fa fa-comment fa-beat" aria-hidden="true" data-toggle="tooltip" title=""
                    data-original-title="{{ trans('user.staff-noted') }}"></i>@endif
                </h2>
                <h4>{{ trans('common.group') }}: <span class="badge-user text-bold"
                                                       style="color:{{ $user->roleColor() }}; background-image:{{ $user->roleEffect() }};"><i
                      class="{{ $user->roleIcon() }}" data-toggle="tooltip" title=""
                      data-original-title="{{ $user->roleName() }}"></i> {{ $user->roleName() }}</span></h4>
                <h4>{{ trans('user.registration-date') }} {{ $user->created_at === null ? "N/A" : date('M d Y', $user->created_at->getTimestamp()) }}</h4>
                <span style="float:left;">
        @if(auth()->user()->id != $user->id)
                    @if(auth()->user()->isFollowing($user->id))
                      <a href="{{ route('unfollow', ['user' => $user->id]) }}" id="delete-follow-{{ $user->target_id }}"
                         class="btn btn-xs btn-info" title="{{ trans('user.unfollow') }}"><i
                          class="fa fa-user"></i> {{ trans('user.unfollow') }} {{ $user->username }}</a>
                    @else
                      <a href="{{ route('follow', ['user' => $user->id]) }}" id="follow-user-{{ $user->id }}"
                         class="btn btn-xs btn-success" title="{{ trans('user.follow') }}"><i
                          class="fa fa-user"></i> {{ trans('user.follow') }} {{ $user->username }}</a>
                    @endif
                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_report"><i
                        class="fa fa-eye"></i> {{ trans('user.report') }}</button>
        </span>
                <span style="float:right;">
        @if(auth()->check() && \App\Policy::isModerator(auth()->user()))
                    @if(\App\Policy::isBanned($user))
                      <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal_user_unban"><span
                          class="fa fa-undo"></span> {{ trans('user.unban') }} </button>
                    @else
                      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_ban"><span
                          class="fa fa-ban"></span> {{ trans('user.ban') }}</button>
                    @endif
                    <a href="{{ route('user_setting', ['id' => $user->id]) }}" class="btn btn-xs btn-warning"><span
                        class="fa fa-pencil"></span> {{ trans('user.edit') }} </a>
                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal_user_delete"><span
                        class="fa fa-trash"></span> {{ trans('user.delete') }} </button>
                  @endif
                  @endif
        </span>
              </div>
            </div>
          </div>
        </div>

        <h3><i class="fa fa-unlock"></i> {{ trans('user.public-info') }}</h3>
        <table class="table table-condensed table-bordered table-striped">
          <tbody>
          <tr>
            <td colspan="2">
              <ul class="list-inline mb-0">
                <li>
                  <span class="badge-extra text-green text-bold"><i
                      class="fa fa-upload"></i> {{ trans('user.total-uploads') }}: {{ $user->torrents->count() }}</span>
                </li>
                <li>
                  <span class="badge-extra text-red text-bold"><i
                      class="fa fa-download"></i> {{ trans('user.total-downloads') }}
                    : {{ $history->where('actual_downloaded', '>', 0)->count() }}</span>
                </li>
                <li>
                  <span class="badge-extra text-green text-bold"><i
                      class="fa fa-cloud-upload"></i> {{ trans('user.total-seeding') }}
                    : {{ $user->getSeeding() }}</span>
                </li>
                <li>
                  <span class="badge-extra text-red text-bold"><i
                      class="fa fa-cloud-download"></i> {{ trans('user.total-leeching') }}
                    : {{ $user->getLeeching() }}</span>
                </li>
              </ul>
            </td>
          </tr>
      </div>
      <tr>
        <td>{{ trans('torrent.downloaded') }}</td>
        <td>
          <span class="badge-extra text-red" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.download-recorded') }}">{{ $user->getDownloaded() }}</span> -
          <span class="badge-extra text-yellow" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.download-bon') }}">N/A</span> =
          <span class="badge-extra text-orange" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.download-true') }}">N/A</span></td>
      </tr>
      <tr>
        <td>{{ trans('torrent.uploaded') }}</td>
        <td>
          <span class="badge-extra text-green" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.upload-recorded') }}">{{ $user->getUploaded() }}</span> -
          <span class="badge-extra text-yellow" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.upload-bon') }}">N/A</span> =
          <span class="badge-extra text-orange" data-toggle="tooltip" title=""
                data-original-title="{{ trans('user.upload-true') }}">N/A</span></td>
      </tr>
      <tr>
        <td>{{ trans('common.ratio') }}</td>
        <td><span class="badge-user group-member">{{ $user->getRatioString() }}</span></td>
      </tr>
      <tr>
        <td>{{ trans('user.total-seedtime-all') }}</td>
        <td><span
            class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed($history->sum('seedtime')) }}</span>
        </td>
      </tr>
      <tr>
        <td>{{ trans('user.avg-seedtime') }}</td>
        <td><span
            class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed(round($history->sum('seedtime') / max(1, $history->count()))) }}</span>
        </td>
      </tr>
      <tr>
        <td>{{ trans('user.badges') }}</td>
        <td>
          @if($user->getSeeding() >= 150)
            <span class="badge-user" style="background-color:#3fb618; color:white;" data-toggle="tooltip" title=""
                  data-original-title="{{ trans('user.certified-seeder-desc') }}"><i
                class="fa fa-upload"></i> {{ trans('user.certified-seeder') }}!</span>
          @endif
          @if($history->where('actual_downloaded', '>', 0)->count() >= 100)
            <span class="badge-user" style="background-color:#ff0039; color:white;" data-toggle="tooltip" title=""
                  data-original-title="{{ trans('user.certified-downloader-desc') }}"><i
                class="fa fa-download"></i> {{ trans('user.certified-downloader') }}!</span>
          @endif
          @if($user->getSeedbonus() >= 50000)
            <span class="badge-user" style="background-color:#9400d3; color:white;" data-toggle="tooltip" title=""
                  data-original-title="{{ trans('user.certified-banker-desc') }}"><i
                class="fa fa-star"></i> {{ trans('user.certified-banker') }}!</span>
          @endif
        </td>
      </tr>
      <tr>
        <td>{{ trans('user.title') }}</td>
        <td>
          <span class="badge-extra">{{ $user->title }}</span>
        </td>
      </tr>
      <tr>
        <td>{{ trans('user.about-me') }}</td>
        <td>
          <span class="badge-extra">@emojione($user->getAboutHtml())</span>
        </td>
      </tr>
      <tr>
        <td>{{ trans('user.extra') }}</td>
        <td>
          <ul class="list-inline mb-0">
            <li>
          <span class="badge-extra"><strong>{{ trans('bon.bon') }}:</strong>
            <span class="text-green text-bold">{{ $user->getSeedbonus() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('common.fl_tokens') }}:</strong>
            <span class="text-green text-bold">{{ $user->fl_tokens }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.thanks-received') }}:</strong>
            <span class="text-pink text-bold">{{ $user->thanksReceived->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.thanks-given') }}:</strong>
            <span class="text-pink text-bold"> {{ $user->thanksGiven->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.article-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('article_id', '>', 0)->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.torrent-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('torrent_id', '>', 0)->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.request-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('requests_id', '>', 0)->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.topics') }}:</strong>
            <span class="text-green text-bold">{{ $user->topics->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.posts') }}:</strong>
            <span class="text-green text-bold">{{ $user->posts->count() }}</span>
          </span>
            </li>
            <li>
          <span class="badge-extra"><strong>{{ trans('user.hit-n-runs-count') }}:</strong>
            <span
              class="{{ $user->hitandruns > 0 ? 'text-red' : 'text-green' }} text-bold">{{ $user->hitandruns }}</span>
          </span>
            </li>
          </ul>
        </td>
      </tr>
      <tr>
        <td>Warnings</td>
        <td>
          <span class="badge-extra text-red text-bold"><strong>{{ trans('user.active-warnings') }}
              : {{ $warnings->count() }} / {!! config('hitrun.max_warnings') !!}</strong></span>
          @if(auth()->check() && \App\Policy::isModerator(auth()->user()))
            <a href="{{ route('warninglog', ['id' => $user->id]) }}"><span
                class="badge-extra text-bold"><strong>{{ trans('user.warning-log') }}</strong></span></a>
          @endif
          <div class="progress">
            <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
                 style="width:.1%;border-bottom-color: #8c0408">
            </div>
            @foreach($warnings as $warning)
              <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
                   style="width:33.3%;border-bottom-color: #8c0408">
                {{ strtoupper(trans('user.warning')) }}
              </div>
            @endforeach
          </div>
        </td>
      </tr>
      </tbody>
      </table>
  </div>

  @if(auth()->check() && (auth()->user()->id == $user->id || \App\Policy::isModerator(auth()->user())))
    <div class="block">
      <h3><i class="fa fa-lock"></i> {{ trans('user.private-info') }}</h3>
      <table class="table table-condensed table-bordered table-striped">
        <tbody>
        <tr>
          <td class="col-sm-3"> {{ trans('user.passkey') }}</td>
          <td>
            <div class="row">
              <div class="col-sm-2">
                <button type="button" class="btn btn-xxs btn-info collapsed" data-toggle="collapse"
                        data-target="#pid_block" aria-expanded="false">{{ trans('user.show-passkey') }}</button>
              </div>
              <div class="col-sm-10">
                <div id="pid_block" class="collapse" aria-expanded="false" style="height: 0px;">
                  <span class="text-monospace">{{ $user->passkey }}</span>
                  <br>
                </div>
                <span class="small text-red">{{ trans('user.passkey-warning') }}</span>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td> {{ trans('user.user-id') }}</td>
          <td>{{ $user->id }}</td>
        </tr>
        <tr>
          <td> {{ trans('common.email') }}</td>
          <td>{{ $user->email }}</td>
        </tr>
        <tr>
          <td> {{ trans('user.last-login') }}</td>
          <td>@if($user->last_login != null){{ $user->last_login->toDayDateTimeString() }}
            ({{ $user->last_login->diffForHumans() }})@else N/A @endif</td>
        </tr>
        <tr>
          <td> {{ trans('user.can-upload') }}</td>
          @if(\App\Policy::canUpload($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.can-download') }}</td>
          @if(\App\Policy::canDownload($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.can-comment') }}</td>
          @if(\App\Policy::canComment($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.can-request') }}</td>
          @if(\App\Policy::canRequest($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.can-chat') }}</td>
          @if(\App\Policy::canChat($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.can-invite') }}</td>
          @if(\App\Policy::canInvite($user))
            <td><i class="fa fa-check text-green"></i></td>
          @else
            <td><i class="fa fa-times text-red"></i></td>
          @endif
        </tr>
        <tr>
          <td> {{ trans('user.invites') }}</td>
          @if (\App\Policy::hasUnlimitedInvites($user))
            <td><span class="text-success text-bold"> ∞</span><a
                href="{{ route('inviteTree', ['id' => $user->id]) }}"><span
                  class="badge-extra text-bold"><strong>{{ trans('user.invite-tree') }}</strong></span></a></td>
          @elseif ($user->invites > 0)
            <td><span class="text-success text-bold"> {{ $user->invites }}</span><a
                href="{{ route('inviteTree', ['id' => $user->id]) }}"><span
                  class="badge-extra text-bold"><strong>{{ trans('user.invite-tree') }}</strong></span></a></td>
          @else
            <td><span class="text-danger text-bold"> {{ $user->invites }}</span><a
                href="{{ route('inviteTree', ['id' => $user->id]) }}"><span
                  class="badge-extra text-bold"><strong>{{ trans('user.invite-tree') }}</strong></span></a></td>
          @endif
        </tr>
        </tbody>
      </table>
      <br>
    </div>

    @if(auth()->check() && auth()->user()->id == $user->id)
      <div class="block">
        <center>
          <a href="{{ route('user_settings', ['id' => $user->id]) }}">
            <button class="btn btn-primary"><span class="fa fa-cog"></span> {{ trans('user.account-settings') }}
            </button>
          </a>
          <a href="{{ route('user_edit_profile', ['id' => $user->id]) }}">
            <button class="btn btn-primary"><span class="fa fa-user"></span> {{ trans('user.edit-profile') }}</button>
          </a>
          <a href="{{ route('invite') }}">
            <button class="btn btn-primary"><span class="fa fa-plus"></span> {{ trans('user.invites') }}</button>
          </a>
          <a href="{{ route('user_clients', ['id' => $user->id]) }}">
            <button class="btn btn-primary"><span class="fa fa-server"></span> {{ trans('user.my-seedboxes') }}</button>
          </a>
        </center>
      </div>
    @endif

    @if(auth()->check() && (auth()->user()->id == $user->id || \App\Policy::isModerator(auth()->user())))
      <div class="block">
        <center>
          <a href="{{ route('myuploads', ['id' => $user->id]) }}">
            <button class="btn"><span class="fa fa-upload"></span> {{ trans('user.uploads-table') }}
            </button>
          </a>
          <a href="{{ route('myactive', ['id' => $user->id]) }}">
            <button class="btn"><i class="far fa-clock"></i> {{ trans('user.active-table') }}
            </button>
          </a>
          <a href="{{ route('myhistory', ['id' => $user->id]) }}">
            <button class="btn"><span class="fa fa-history"></span> {{ trans('user.history-table') }}
            </button>
          </a>
        </center>
      </div>
    @endif

    <div class="block">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#hr" aria-controls="hitrun" role="tab"
                                   data-toggle="tab">{{ trans('user.hit-n-runs') }}</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <!-- HitRun -->
        <div role="tabpanel" class="tab-pane active" id="hr">
          <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
              <div class="head"><strong>{{ trans('user.hit-n-runs-history') }}</strong></div>
              <thead>
              <th>{{ trans('torrent.torrent') }}</th>
              <th>{{ trans('user.warned-on') }}</th>
              <th>{{ trans('user.expires-on') }}</th>
              <th>{{ trans('user.active') }}</th>
              </thead>
              <tbody>
              @foreach($hitrun as $hr)
                <tr>
                  <td>
                    <a class="view-torrent" data-id="{{ $hr->torrenttitle->id }}"
                       data-slug="{{ $hr->torrenttitle->slug }}"
                       href="{{ route('torrent', array('id' => $hr->torrenttitle->id)) }}" data-toggle="tooltip"
                       title="" data-original-title="{{ $hr->torrenttitle->name }}">{{ $hr->torrenttitle->name }}</a>
                  </td>
                  <td>
                    {{ $hr->created_at }}
                  </td>
                  <td>
                    {{ $hr->expires_on }}
                  </td>
                  <td>
                    @if($hr->active == 1)
                      <span class='label label-success'>{{ trans('common.yes') }}</span>
                    @else
                      <span class='label label-danger'>{{ trans('user.expired') }}</span>
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
            {{ $hitrun->links() }}
          </div>
        </div>
        <!-- /HitRun -->
      </div>
    </div>
    @endif
    @endif
    </div>

    @include('user.user_modals', ['user' => $user]) @endsection
