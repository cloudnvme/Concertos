@extends('layout.next')

@section('title')
  <title>{{ $torrent->name }} - {{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')
  <link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('meta')
  <meta name="description" content="{{ trans('torrent.meta-desc', ['name' => $torrent->name]) }}!">
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
    </a>
  </li>
  <li class="active">
    <a href="{{ route('torrent', ['id' => $torrent->id]) }}" itemprop="url"
       class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
    </a>
  </li>
@endsection


@section('content')
  <div class="torrent">
    <div class="buttons mbox mbox--small-bottom">
      <a href="{{ route('download', ['id' => $torrent->id]) }}">
        <button class="btn">
          <i class="fa fa-download"></i>
          Download
        </button>
      </a>

      <a href="{{ route('torrentThank', ['id' => $torrent->id]) }}">
        <button class="btn">
          <i class="fas fa-hand-holding-heart"></i>
          Thank Uploader
        </button>
      </a>

      @if (auth()->user()->hasBookmarked($torrent->id))
        <a href="{{ route('unbookmark', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fa fa-bookmark"></i>
            Remove Bookmark
          </button>
        </a>
      @else
        <a href="{{ route('bookmark', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fa fa-bookmark"></i>
            Bookmark
          </button>
        </a>
      @endif
      @if (!\App\Policy::isModerator($user) && $user->id === $torrent->user->id)
        <a href="{{ route('edit', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-edit"></i>
            Edit
          </button>
        </a>

        <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-eraser"></i>
            Delete
          </button>
        </a>
      @endif

      @if (\App\Policy::isModerator($user))
        @if ($torrent->free)
          <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-star"></i>
              Revoke Freeleech
            </button>
          </a>
        @else
          <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-star"></i>
              Grant Freeleech
            </button>
          </a>
        @endif

        @if ($torrent->doubleup)
          <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-gem"></i>
              Revoke Double Upload
            </button>
          </a>
        @else
          <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-gem"></i>
              Grant Double Upload
            </button>
          </a>
        @endif

        <a href="{{ route('torrent_sticky', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-thumbtack"></i>
            Sticky
          </button>
        </a>

        <a href="{{ route('bumpTorrent', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-fire"></i>
            Bump
          </button>
        </a>

        @if ($torrent->featured)
          <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-certificate"></i>
              Revoke Feature
            </button>
          </a>
        @else
          <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
            <button class="btn">
              <i class="fa fa-certificate"></i>
              Feature
            </button>
          </a>
        @endif

        <a href="{{ route('edit', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-edit"></i>
            Edit
          </button>
        </a>

        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-users"></i>
            Peers
          </button>
        </a>

        <a href="{{ route('history', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-history"></i>
            History
          </button>
        </a>

        <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
          <button class="btn">
            <i class="fas fa-eraser"></i>
            Delete
          </button>
        </a>
      @endif
    </div>

    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Info</div>
      <div class="block__content">
        <table class="table">
          <tbody>
          <tr>
            <td class="torrent__meta-title">Name</td>
            <td>
              {{ $torrent->name }}

            </td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Discounts</td>
            <td>
              @if ($torrent->free)
                <span class="badge badge--user mbox mbox--mini-right">
              <a class="link" href="{{ route('torrents', ['freeleech' => 'on']) }}">Freeleech</a>
            </span>
              @endif

              @if ($torrent->doubleup)
                <span class="badge badge--user mbox mbox--mini-right">
              <a class="link" href="{{ route('torrents', ['doubleupload' => 'on']) }}">Double Upload</a>
            </span>
              @endif

              @if ($torrent->featured)
                <span class="badge badge--user mbox mbox--mini-right">
              <a class="link" href="{{ route('torrents', ['featured' => 'on']) }}">Featured</a>
            </span>
              @endif

              @if (config('other.freeleech'))
                <span class="badge badge--user mbox mbox--mini-right">
              <a class="link" href="{{ route('torrents') }}">Global Freeleech</a>
            </span>
              @endif

              @if (\App\Policy::isFreeleech($user))
                <span class="badge badge--user mbox mbox--mini-right">
              <a class="link" href="{{ route('torrents') }}">Special Freeleech</a>
            </span>
              @endif
            </td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Uploader</td>
            <td>
              @if ($torrent->anon)
                <i class="fa fa-question-circle"></i>
                Anonymous
                @if (\App\Policy::isModerator($user))
                  (<a class="link"
                      href="{{ route('profile', ['id' => $torrent->user->id]) }}">{{ $torrent->user->username }}</a>)
                @endif
              @else
                <i class="{{ $torrent->user->roleIcon() }}"></i>
                {{ $torrent->user->roleName() }}
                <a class="link"
                   href="{{ route('profile', ['id' => $torrent->user->id]) }}">{{ $torrent->user->username }}</a>
              @endif

              <span class="badge badge--extra text-bold">
            <i class="fa fa-heart"></i>
                {{ $torrent->thanks->count() }} Thanks
          </span>
            </td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Uploaded</td>
            <td>{{ $torrent->getAge() }}</td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Size</td>
            <td>{{ \App\Helpers\StringHelper::formatBytes($torrent->size) }}</td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Estimated Ratio After Download</td>
            <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}</td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Category</td>
            <td>
              <a class="link"
                 href="{{ route('torrents', ['category_' . $torrent->category->id => 'on']) }}">{{ $torrent->category->name }}</a>
            </td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Type</td>
            <td>
              <a class="link"
                 href="{{ route('torrents', ['type_' . $torrent->type->id => 'on']) }}">{{ $torrent->type->name }}</a>
            </td>
          </tr>
          @if ($torrent->tags->isNotEmpty())
            <tr>
              <td class="torrent__meta-title">Tags</td>
              <td>
                <div class="tags">
                  @foreach ($torrent->tags as $tag)
                    <span class="tags__tag badge badge--user">
                      <a class="link" href="{{ route('torrents', ['tags' => $tag->name]) }}">{{ $tag->name }}</a>
                    </span>
                  @endforeach
                </div>
              </td>
            </tr>
          @endif
          <tr>
            <td class="torrent__meta-title">Info Hash</td>
            <td>{{ $torrent->info_hash }}</td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Peers</td>
            <td>
              <span class="badge badge--extra text-bold mbox mbox--mini-right">
              <i class="fa fa-arrow-up"></i>
              {{ $torrent->seeders }} Seeders
              </span>

              <span class="badge badge--extra text-bold mbox mbox--mini-right">
                <i class="fa fa-arrow-down"></i>
                {{ $torrent->leechers }} Leechers
              </span>

              <span class="badge badge--extra text-bold mbox mbox--mini-right">
                <i class="fa fa-download"></i>
                {{ $torrent->times_completed }} Times Completed
              </span>
            </td>
          </tr>

          @if ($tmdb_link !== null || $imdb_link !== null)
            <tr>
              <td class="torrent__meta-title">Links</td>
              <td>
                @if ($tmdb_link !== null)
                  <a class="link" href="{{ $tmdb_link }}">TMDB</a>
                @endif

                @if ($imdb_link != null)
                  <a class="link" href="{{ $imdb_link }}">IMDB</a>
                @endif

              </td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
    </div>

    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Description</div>
      <div class="block__content">
        {!! $torrent->getDescriptionHtml() !!}
      </div>
    </div>

    @if ($torrent->mediainfo !== null)
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">MediaInfo</div>
        <div class="block__content code pre">{{ $torrent->mediainfo }}</div>
      </div>
    @endif

    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Files</div>
      <div class="block__content scrollable-y code">
        <table class="table">
          <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('common.name') }}</th>
            <th>{{ trans('torrent.size') }}</th>
          </tr>
          </thead>
          <tbody>
          @foreach($torrent->files as $k => $f)
            <tr>
              <td>{{ $k + 1 }}</td>
              <td>{{ $f->name }}</td>
              <td>{{ $f->getSize() }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>

    @if ($comments->isNotEmpty())
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Comments</div>
        <div class="block__content">
          {!! $torrent->renderComments() !!}
        </div>
      </div>
    @endif

    <form class="comment-editor block" action="{{ route('comment_torrent', ['id' => $torrent->id]) }}">
      <div class="block__title">Your Comment</div>
      <div class="block__content">
        <textarea class="textarea textarea--vertical" id="content" name="content" cols="30" rows="5"></textarea>
        <button type="submit" class="btn">
          <i class="fa fa-paper-plane"></i>
          {{ trans('common.submit') }}
        </button>
        <label class="v-checkbox">
          <input type="checkbox" name="anonymous"/>
          <span></span>
          Anonymous comment
        </label>
      </div>
    </form>

  </div>
@endsection