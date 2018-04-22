@extends('layout.default')

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
  <div class="torrent box container">
    <h2 id="info">Info</h2>
    <table class="table table-condensed">
      <tbody>
      <tr>
        <td>Name</td>
        <td>
          {{ $torrent->name }}
          <a href="{{ route('download', ['id' => $torrent->id]) }}">
            <input type="button" class="v-button" value="Download"/>
          </a>

          @if (auth()->user()->hasBookmarked($torrent->id))
            <a href="{{ route('unbookmark', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Remove Bookmark"/>
            </a>
          @else
            <a href="{{ route('bookmark', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Bookmark"/>
            </a>
          @endif
          @if (!$user->group->is_modo && $user->id === $torrent->user->id)
            <a href="{{ route('edit', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Edit"/>
            </a>

            <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Delete"/>
            </a>
          @endif
        </td>
      </tr>
      <tr>
        <td>Discounts</td>
        <td>
          @if ($torrent->free)
            <span class="badge-user">
              <a class="link" href="{{ route('torrents', ['freeleech' => 'on']) }}">Freeleech</a>
            </span>
          @endif

          @if ($torrent->doubleup)
            <span class="badge-user">
              <a class="link" href="{{ route('torrents', ['doubleupload' => 'on']) }}">Double Upload</a>
            </span>
          @endif

          @if ($torrent->featured)
            <span class="badge-user">
              <a class="link" href="{{ route('torrents', ['doubleupload' => 'on']) }}">Featured</a>
            </span>
          @endif
        </td>
      </tr>
      <tr>
        <td>Uploader</td>
        <td>
          @if ($torrent->anon)
            <i class="fa fa-question-circle"></i>
            Anonymous
            @if ($user->group->is_modo)
              (<a class="link" href="{{ route('profile', ['id' => $user->id]) }}">{{ $torrent->user->username }}</a>)
            @endif
          @else
            <i class="{{ $torrent->user->group->icon }}"></i>
            {{ $torrent->user->group->name }}
            <a class="link" href="{{ route('profile', ['id' => $user->id]) }}">{{ $torrent->user->username }}</a>
          @endif
          <a href="{{ route('torrentThank', ['id' => $torrent->id]) }}">
            <input type="button" class="v-button" value="Thank Uploader"/>
          </a>
          <span class="badge-extra text-bold">
            <i class="fa fa-heart"></i>
            {{ $torrent->thanks->count() }} Thanks
          </span>
        </td>
      </tr>
      <tr>
        <td>Uploaded</td>
        <td>{{ $torrent->age() }}</td>
      </tr>
      <tr>
        <td>Size</td>
        <td>{{ \App\Helpers\StringHelper::formatBytes($torrent->size) }}</td>
      </tr>
      <tr>
        <td>Estimated Ratio After Download</td>
        <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}</td>
      </tr>
      <tr>
        <td>Category</td>
        <td>
          <a class="link" href="{{ route('torrents', ['category_' . $torrent->category->id => 'on']) }}">{{ $torrent->category->name }}</a>
        </td>
      </tr>
      <tr>
        <td>Type</td>
        <td>{{ $torrent->type }}</td>
      </tr>
      @if ($torrent->tags->isNotEmpty())
        <tr>
          <td>Tags</td>
          <td>
            <div class="torrent-tags">
              @foreach ($torrent->tags as $tag)
                <a href="{{ route('torrents', ['tags' => $tag->name]) }}"><span class="tag">{{ $tag->name }}</span></a>
              @endforeach
            </div>
          </td>
        </tr>
      @endif
      <tr>
        <td>Info Hash</td>
        <td>{{ $torrent->info_hash }}</td>
      </tr>
      <tr>
        <td>Peers</td>
        <td>
          <span class="badge-extra text-bold">
            <i class="fa fa-arrow-up"></i>
            {{ $torrent->seeders }} Seeders
          </span>
          <span class="badge-extra text-bold">
            <i class="fa fa-arrow-down"></i>
            {{ $torrent->leechers }} Leechers
          </span>
          <span class="badge-extra text-bold">
            <i class="fa fa-download"></i>
            {{ $torrent->times_completed }} Times Completed
          </span>
        </td>
      </tr>
      @if ($user->group->is_modo)
        <tr>
          <td>Moderation</td>
          <td>
            @if ($torrent->free)
              <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Revoke Freeleech"/>
              </a>
            @else
              <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Grant Freeleech"/>
              </a>
            @endif

            @if ($torrent->doubleup)
              <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Revoke Double Upload"/>
              </a>
            @else
              <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Grant Double Upload"/>
              </a>
            @endif

            <a href="{{ route('torrent_sticky', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Sticky"/>
            </a>

            <a href="{{ route('bumpTorrent', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Bump"/>
            </a>

            @if ($torrent->featured)
              <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Revoke Feature"/>
              </a>
            @else
              <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
                <input type="button" class="v-button" value="Feature"/>
              </a>
            @endif

            <a href="{{ route('edit', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Edit"/>
            </a>

            <a href="{{ route('peers', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Peers"/>
            </a>

            <a href="{{ route('history', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="History"/>
            </a>

            <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
              <input type="button" class="v-button" value="Delete"/>
            </a>

          </td>
        </tr>
      @endif
      </tbody>
    </table>

    <h2 id="description">Description</h2>
    <div class="description">
      @emojione($torrent->getDescriptionHtml())
    </div>

    @if ($torrent->mediainfo !== null)
      <h2 id="mediainfo">Mediainfo</h2>
      <div class="mediainfo">{{ $torrent->mediainfo }}</div>
    @endif

    @if ($comments->isNotEmpty())
      <h2 id="comments">Comments</h2>
      @foreach ($comments as $comment)
        <span class="username">
          @if ($comment->anon)
            <i class="fa fa-question-circle"></i>
            Anonymous
            @if ($user->group->is_modo)
              (<a class="link" href="{{ route('profile', ['id' => $comment->user->id]) }}">{{ $comment->user->username }}</a>)
            @endif
          @else
            <i class="{{ $comment->user->group->icon }}"></i>
            {{ $comment->user->group->name }}
            <a class="link" href="{{ route('profile', ['id' => $comment->user->id]) }}">{{ $comment->user->username }}</a>
          @endif
          wrote {{ $comment->created_at->diffForHumans() }}:
          @if ($user->id === $comment->user_id || $user->group_is_modo)
            <a href="/comment/delete/{{ $comment->id }}">
              <input type="button" class="v-button m" value="Delete">
            </a>
          @endif
        </span>
        <div class="comment">
          @emojione($comment->getContentHtml())
        </div>
      @endforeach
      {{ $comments->fragment('comments')->links() }}
    @endif

    <form class="comment-editor" action="{{ route('comment_torrent', ['id' => $torrent->id]) }}">
      <div class="form-group">
        <label for="content">{{ trans('common.your-comment') }}:</label><span class="badge-extra">{{ trans('common.type') }}
          <strong>:</strong> {{ trans('common.for') }} emoji</span> <span
          class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span>
        <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
      </div>
      <button type="submit" class="v-button">{{ trans('common.submit') }}</button>
      <label class="v-checkbox">
        <input type="checkbox" name="anonymous"/>
        <span></span>
        Anonymous comment
      </label>
    </form>

  </div>
@endsection