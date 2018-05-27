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
      <div class="profile-block__title">{!! $user->getFullName() !!}</div>
      <div class="profile-block__age">Member since {{ $user->created_at }}</div>

      <a href="{{ route('create', ['id' => auth()->user(), 'to' => $user->username]) }}">
        <button class="btn btn-light">
          <i class="fas fa-pencil-alt"></i>
          Send PM
        </button>
      </a>

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

        <a href="{{ route('user_setting', ['id' => $user->id]) }}">
          <button class="btn btn-light">
            <i class="far fa-edit"></i>
            Edit User
          </button>
        </a>
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
          <td>Groups</td>
          <td>{{ $user->rolesAsString() }}</td>
        </tr>

        <tr>
          <td>Total Downloaded</td>
          <td>{{ $user->getDownloaded() }}</td>
        </tr>

        <tr>
          <td>Total Uploaded</td>
          <td>{{ $user->getUploaded() }}</td>
        </tr>

        <tr>
          <td>Ratio</td>
          <td>{{ $user->getRatio() }}</td>
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
          <td>{{ $user->countSeedingTorrents() }}</td>
        </tr>

        <tr>
          <td>Total Leeching</td>
          <td>{{ $user->countLeechingTorrents() }}</td>
        </tr>

        <tr>
          <td>Title</td>
          <td>{{ $user->title }}</td>
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

  @if ($user->about != null)
    <div class="block mbox mbox--small-bottom">
      <div class="block__title">About Me</div>
      <div class="block__content">
        {!! $user->getAboutHtml() !!}
      </div>
    </div>
  @endif

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
            <td>Email</td>
            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
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

    <div class="block mbox mbox--small-bottom">
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

    @if (\App\Policy::isModerator(auth()->user()) && !\App\Policy::isBanned($user))
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Ban User</div>
        <div class="block__content">
          <form action="{{ route('ban', ['id' => $user->id]) }}" method="POST">
            @csrf
            <p>If you're sure that you want to ban that user, you need to specify a reason.</p>
            <div class="flex">
              <div class="badge badge col col--small badge--centered mbox mbox--mini-right">
                Reason
              </div>
              <input class="flex__expanded" type="text" name="reason"/>
            </div>
            <button class="btn">
              <i class="fas fa-ban"></i>
              Ban User
            </button>
          </form>
        </div>
      </div>
    @elseif (\App\Policy::isModerator(auth()->user()))
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Unban User</div>
        <div class="block__content">
          <form action="{{ route('unban', ['id' => $user->id]) }}" method="POST">
            @csrf
            <p>If you're sure that you want to unban that user, you need to specify a reason and a new role.</p>
            <div class="flex mbox mbox--small-bottom">
              <div class="badge badge col col--small badge--centered mbox mbox--mini-right">
                Reason
              </div>
              <input class="flex__expanded" type="text" name="reason"/>
            </div>
            <div class="flex">
              <div class="badge badge col col--small badge--centered mbox mbox--mini-right">
                Role
              </div>

              <input class="flex__expanded" type="text" name="role"/>
            </div>

            <button class="btn">
              <i class="fas fa-ban"></i>
              Unban User
            </button>
          </form>
        </div>
      </div>
    @endif

    @if (\App\Policy::isModerator(auth()->user()))
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Delete User</div>
        <div class="block__content">
          <form>
            <p>If you're sure that you want to delete that user, type DELETE in the textbox.</p>
            <div class="flex">
              <div class="badge badge col col--small badge--centered mbox mbox--mini-right">
                Confirm
              </div>

              <input class="flex__expanded" type="text" name="reason"/>
            </div>
            <div class="flex">
              <button class="btn">
                <i class="fas fa-eraser"></i>
                Delete
              </button>
            </div>

          </form>

        </div>
      </div>
    @endif

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