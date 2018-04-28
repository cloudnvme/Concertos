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
    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Info</div>
      <div class="block__content">
        <table class="table">
          <tbody>
          <tr>
            <td class="torrent__meta-title">Name</td>
            <td>
              {{ $torrent->name }}
              <a href="{{ route('download', ['id' => $torrent->id]) }}">
                <input type="button" class="btn" value="Download"/>
              </a>

              @if (auth()->user()->hasBookmarked($torrent->id))
                <a href="{{ route('unbookmark', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Remove Bookmark"/>
                </a>
              @else
                <a href="{{ route('bookmark', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Bookmark"/>
                </a>
              @endif
              @if (!\App\Policy::isModerator($user) && $user->id === $torrent->user->id)
                <a href="{{ route('edit', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Edit"/>
                </a>

                <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Delete"/>
                </a>
              @endif
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
              <a href="{{ route('torrentThank', ['id' => $torrent->id]) }}">
                <input type="button" class="btn" value="Thank Uploader"/>
              </a>
              <span class="badge badge--extra text-bold">
            <i class="fa fa-heart"></i>
                {{ $torrent->thanks->count() }} Thanks
          </span>
            </td>
          </tr>
          <tr>
            <td class="torrent__meta-title">Uploaded</td>
            <td>{{ $torrent->age() }}</td>
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
          @if (\App\Policy::isModerator($user))
            <tr>
              <td class="torrent__meta-title">Moderation</td>
              <td>
                @if ($torrent->free)
                  <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Revoke Freeleech"/>
                  </a>
                @else
                  <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Grant Freeleech"/>
                  </a>
                @endif

                @if ($torrent->doubleup)
                  <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Revoke Double Upload"/>
                  </a>
                @else
                  <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Grant Double Upload"/>
                  </a>
                @endif

                <a href="{{ route('torrent_sticky', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Sticky"/>
                </a>

                <a href="{{ route('bumpTorrent', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Bump"/>
                </a>

                @if ($torrent->featured)
                  <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Revoke Feature"/>
                  </a>
                @else
                  <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}">
                    <input type="button" class="btn" value="Feature"/>
                  </a>
                @endif

                <a href="{{ route('edit', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Edit"/>
                </a>

                <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Peers"/>
                </a>

                <a href="{{ route('history', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="History"/>
                </a>

                <a href="{{ route('confirm_delete', ['id' => $torrent->id]) }}">
                  <input type="button" class="btn" value="Delete"/>
                </a>

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
        @emojione($torrent->getDescriptionHtml())
      </div>
    </div>

    @if ($torrent->mediainfo !== null)
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Mediainfo</div>
        <div class="block__content code">{{ $torrent->mediainfo }}</div>
      </div>
    @endif

    @if ($comments->isNotEmpty())
      <div class="block mbox mbox--small-bottom">
        <div class="block__title">Comments</div>
        <div class="block__content">
          @foreach ($comments as $comment)
            <div class="message">
              <div class="message__info">
                <div style="color: {{ $comment->user->roleColor() }}" class="message__user">
                  <a class="link" href="{{ route('profile', ['id' => $comment->user->id]) }}">
                    <i class="{{ $comment->user->roleIcon() }}"></i>
                    {{ $comment->user->roleName() }}
                    {{ $comment->user->username }}
                  </a>
                </div>
                @if ($comment->user->image != null)
                  <img class="message__avatar" src="{{ url("files/img/{$comment->user->image}") }}"></img>
                @else
                  <img class="message__avatar" src="{{ url("img/profile.png") }}"></img>
                @endif
                <div class="message__time">{{ $comment->created_at }}</div>
              </div>
              <div class="message__text">@emojione($comment->getContentHtml())</div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <form class="comment-editor block" action="{{ route('comment_torrent', ['id' => $torrent->id]) }}">
      <div class="block__title">Your Comment</div>
      <div class="block__content">
        <textarea class="textarea textarea--vertical" id="content" name="content" cols="30" rows="5"></textarea>
        <button type="submit" class="btn">{{ trans('common.submit') }}</button>
        <label class="v-checkbox">
          <input type="checkbox" name="anonymous"/>
          <span></span>
          Anonymous comment
        </label>
      </div>
    </form>

  </div>
@endsection