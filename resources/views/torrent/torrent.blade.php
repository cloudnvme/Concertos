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
    <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
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
          <a href="/download/{{ $torrent->slug }}.{{ $torrent->id }}">
            <input type="button" class="v-button" value="Download"/>
          </a>

          @if (auth()->user()->hasBookmarked($torrent->id))
            <a href="/torrents/unbookmark/{{ $torrent->id }}">
              <input type="button" class="v-button" value="Remove Bookmark"/>
            </a>
          @else
            <a href="/torrents/bookmark/{{ $torrent->id }}">
              <input type="button" class="v-button" value="Bookmark"/>
            </a>
          @endif
          @if (!$user->group->is_modo && $user->id === $torrent->user->id)
            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/edit">
              <input type="button" class="v-button" value="Edit"/>
            </a>
          @endif
        </td>
      </tr>
      <tr>
        <td>Discounts</td>
        <td>
          @if ($torrent->free)
            <span class="badge-user">
              Freeleech
            </span>
          @endif

          @if ($torrent->doubleup)
            <span class="badge-user">
              Double Upload
            </span>
          @endif

          @if ($torrent->featured)
            <span class="badge-user">
              Featured
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
              ({{ $torrent->user->username }})
            @endif
          @else
            <i class="{{ $torrent->user->group->icon }}"></i>
            {{ $torrent->user->group->name }}
            {{ $torrent->user->username }}
          @endif
          <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/thank">
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
        <td>{{ $torrent->category->name }}</td>
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
                <a href="/torrents/?tags={{$tag->name}}"><span class="tag">{{ $tag->name }}</span></a>
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
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_fl">
                <input type="button" class="v-button" value="Revoke Freeleech"/>
              </a>
            @else
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_fl">
                <input type="button" class="v-button" value="Grant Freeleech"/>
              </a>
            @endif

            @if ($torrent->doubleup)
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_doubleup">
                <input type="button" class="v-button" value="Revoke Double Upload"/>
              </a>
            @else
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_doubleup">
                <input type="button" class="v-button" value="Grant Double Upload"/>
              </a>
            @endif

            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_sticky">
              <input type="button" class="v-button" value="Sticky"/>
            </a>

            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/bumpTorrent">
              <input type="button" class="v-button" value="Bump"/>
            </a>

            @if ($torrent->featured)
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_feature">
                <input type="button" class="v-button" value="Revoke Feature"/>
              </a>
            @else
              <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/torrent_feature">
                <input type="button" class="v-button" value="Feature"/>
              </a>
            @endif

            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/edit">
              <input type="button" class="v-button" value="Edit"/>
            </a>

            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/peers">
              <input type="button" class="v-button" value="Peers"/>
            </a>

            <a href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}/history">
              <input type="button" class="v-button" value="History"/>
            </a>

            <a href="/torrent/{{ $torrent->id }}/confirm_delete">
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
              ({{ $comment->user->username }})
            @endif
          @else
            <i class="{{ $comment->user->group->icon }}"></i>
            {{ $comment->user->group->name }}
            {{ $comment->user->username }}
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

    <form class="comment-editor" action="/comment/torrent/{{ $torrent->slug }}.{{ $torrent->id }}">
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