@extends('layout.next')

@section('title')
  <title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
  <meta name="description" content="{{ 'Torrents ' . config('other.title') }}">
@endsection

@section('breadcrumb')
  <li class="active">
    <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
    </a>
  </li>
@endsection

@section('content')
  <div class="container box">
    <form class="torrent-search" action="/torrents" method="get">
      <div class="flex mbox mbox--small-bottom">
        <label for="search" class="col col--small badge badge--centered mbox mbox--mini-right">Name</label>
        <input type="text" class="flex__expanded" name="title" placeholder="Title"/>
      </div>

      <div class="flex mbox mbox--small-bottom">
        <label for="uploader" class="col col--small badge badge--centered mbox mbox--mini-right">Uploader</label>
        <input type="text" class="flex__expanded" name="uploader" placeholder="Username of uploader"/>
      </div>

      <div class="flex mbox mbox--small-bottom">
        <label class="col col--small badge badge--centered mbox mbox--mini-right">IDs</label>
        <input type="text" class="flex__expanded mbox mbox--small-right" name="tmdb" placeholder="TMDB ID"/>
        <input type="text" class="flex__expanded" name="imdb" placeholder="IMDB ID"/>
      </div>

      <div class="flex mbox mbox--small-bottom">
        <label class="col col--small badge badge--centered mbox mbox--mini-right">Tags</label>
        <input type="text" class="flex__expanded" name="tags" placeholder="Tags, separated by commas"/>
      </div>

      <div class="flex flex--fluid mbox mbox--small-bottom">
        <span class="col col--small badge badge--centered mbox mbox--small-right">Categories</span>
        @foreach($categories as $category)
          <div class="badge badge--extra mbox mbox--small-right">
            <label class="v-checkbox">
              <input type="checkbox" name="category_{{ $category->id }}"/>
              <span></span>
              <i class="{{ $category->getIcon()}}"></i>
              {{ $category->getName() }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="flex flex--fluid mbox mbox--small-bottom">
        <span class="col col--small badge badge--centered mbox mbox--small-right mbox--small-bottom">Types</span>
        @foreach($repository->types() as $id => $type)
          <div class="badge badge--extra mbox mbox--small-right mbox--small-bottom">
            <label class="v-checkbox">
              <input type="checkbox" name="type_{{ $id }}"/>
              <span></span>
              {{ $type }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="flex mbox mbox--small-bottom">
        <span class="badge badge--centered col col--small mbox mbox--small-right">Discount</span>
        <div class="badge mbox mbox--small-right">
          <label class="v-checkbox v-checkbox--light">
            <input id="freeleech" name="freeleech" type="checkbox">
            <span></span>
            <i class="fa fa-star"></i>
            Freeleech
          </label>
        </div>

        <div class="badge mbox mbox--small-right">
          <label class="v-checkbox v-checkbox--light">
            <input id="doubleupload" name="doubleupload" type="checkbox">
            <span></span>
            <i class="fa fa-gem"></i>
            Double Upload
          </label>
        </div>

        <div class="badge mbox mbox--small-right">
          <label class="v-checkbox v-checkbox--light">
            <input id="featured" name="featured" type="checkbox">
            <span></span>
            <i class="fa fa-certificate"></i>
            Featured Torrent
          </label>
        </div>
      </div>

      <div class="flex mbox mbox--small-bottom">
        <div for="ordering" class="col col--small badge badge--centered mbox mbox--mini-right">Ordering</div>
        <select class="mbox mbox--mini-right" name="order_by">
          <option value="created_at">Time</option>
          <option value="seeders">Seeders</option>
          <option value="leechers">Leechers</option>
          <option value="times_completed">Completed</option>
        </select>
        <select name="direction">
          <option value="desc">Descending</option>
          <option value="asc">Ascending</option>
        </select>
      </div>

      <button type="submit" class="btn mbox mbox--bottom">
        <i class="fa fa-search"></i>
        Search
      </button>
    </form>

    <div class="block mbox mbox--bottom" id="stats">
      <strong>Stats:</strong>
      <span class="stats__item badge badge--extra text-bold">
        <i class="fa fa-file text-thin">
          {{ $count }} Torrents
        </i>
      </span>
      <span class="stats__item badge badge--extra">
        <i class="fa fa-smile text-thin">
          {{ $alive }} Alive
        </i>
      </span>
      <span class="stats__item badge badge--extra">
        <i class="fa fa-frown text-thin">
          {{ $dead }} Dead
        </i>
      </span>
    </div>

    <div class="block">
      <table class="table table--bordered torrents">
        <thead>
        <tr class="torrents__header">
          <th class="torrents__header-category">Category</th>
          <th class="torrents__header-name">Name</th>
          <th class="torrents__header_time">Time</th>
          <th class="torrents__header-size">Size</th>
          <th class="seeders">S</th>
          <th class="leechers">L</th>
          <th class="completed">C</th>
        </tr>
        </thead>
        @foreach($torrents as $torrent)
          <tr class="torrents__row">
            <td>
              <i class="torrent-icon {{ $torrent->category->icon }}"></i>
              <a class="link" href="{{ route('torrents', ['type_' . $torrent->type->id => 'on']) }}">{{ $torrent->type->name }}</a>
              <a class="link"
                 href="{{ route('torrents', ['category_' . $torrent->category->id => 'on']) }}">{{ $torrent->category->name }}</a>
            </td>
            <td>
              <a class="torrents__title link" href="{{ route('torrent', ['id' => $torrent->id]) }}">
                {{ $torrent->name }}
              </a>
              @if ($torrent->tags->isNotEmpty())
                <div class="tags">
                  @foreach($torrent->tags->take(20) as $tag)
                    <span class="link tags__tag badge badge--user badge--condensed mbox mbox--small-right mbox--small-bottom">
                      <a href="{{ route('torrents', ['tags' => $tag->name]) }}">{{ $tag->name }}</a>
                    </span>
                  @endforeach
                </div>
              @endif
              <div>
              </div>
              <div class="flex flex--fluid">
                @if(!$torrent->anon)
                  <span class="badge mbox--small-right mbox--mini-bottom">
                  <i class="fa fa-upload"></i>
                  By <a class="link"
                        href="{{ route('profile', ['id' => $torrent->user->id]) }}">{{ $torrent->user->username }}</a>
                </span>
                @endif
                <span class="badge mbox--small-right mbox--mini-bottom">
                  <i class="fa fa-heart"></i>
                  {{ $torrent->thanks->count() }}
                </span>
                @if($torrent->free)
                  <span class="badge mbox--small-right mbox--mini-bottom">
                    <i class="fa fa-star"></i>
                    <a class="link" href="{{ route('torrents', ['freeleech' => 'on']) }}">Freeleech</a>
                  </span>
                @endif
                @if($torrent->doubleup)
                  <span class="badge mbox--small-right mbox--mini-bottom">
                    <i class="fa fa-gem"></i>
                    <a class="link" href="{{ route('torrents', ['doubleupload' => 'on']) }}">Double Upload</a>
                  </span>
                @endif
                @if($torrent->featured)
                  <span class="badge mbox--small-right mbox--mini-bottom">
                    <i class="fa fa-certificate"></i>
                    <a class="link" href="{{ route('torrents', ['featured' => 'on']) }}">Featured</a>
                  </span>
                @endif
                @if(config('other.freeleech'))
                  <span class="badge mbox--small-right mbox--mini-bottom">
                    <i class="fa fa-star"></i>
                    <a class="link" href="{{ route('torrents') }}">Global Freeleech</a>
                  </span>
                @endif
                @if(\App\Policy::isFreeleech($user))
                  <span class="badge mbox--small-right mbox--mini-bottom">
                    <i class="fa fa-star"></i>
                    <a class="link" href="{{ route('torrents') }}">Special Freeleech</a>
                  </span>
                @endif
              </div>
            </td>
            <td class="torrents__age">{{ $torrent->getAge() }}</td>
            <td class="torrents__size">{{ \App\Helpers\StringHelper::formatBytes($torrent->size) }}</td>
            <td>{{ $torrent->seeders }}</td>
            <td>{{ $torrent->leechers }}</td>
            <td>{{ $torrent->times_completed }}</td>
          </tr>
        @endforeach
      </table>
    </div>
    {{ $torrents->appends($request->all())->links() }}
  </div>
@endsection
