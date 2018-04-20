@extends('layout.default')

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
      <div class="form-group">
        <label for="search" class="col-sm-1 label label-default">Name</label>
        <input type="text" class="bar" name="title" placeholder="Title"/>
      </div>
      <div class="form-group">
        <label for="uploader" class="col-sm-1 label label-default">Uploader</label>
        <input type="text" class="bar" name="uploader" placeholder="Username of uploader"/>
      </div>
      <div class="form-group">
        <label class="col-sm-1 label label-default">IDs</label>
        <input type="text" class="bar" name="tmdb" placeholder="TMDB ID"/>
        <input type="text" class="bar" name="imdb" placeholder="IMDB ID"/>
      </div>

      <div class="form-group">
        <label class="col-sm-1 label label-default">Tags</label>
        <input type="text" class="bar" name="tags" placeholder="Tags, separated by commas"/>
      </div>

      <div class="form-group">
        <span class="label label-default">Categories</span>
        @foreach($repository->categories() as $id => $category)
          <div class="badge-extra">
            <label class="v-checkbox">
              <input type="checkbox" name="category_{{ $id }}"/>
              <span></span>
              {{ $category }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="form-group">
        <span class="label label-default">Types</span>
        @foreach($repository->types() as $id => $type)
          <div class="badge-extra">
            <label class="v-checkbox">
              <input type="checkbox" name="type_{{ $id }}"/>
              <span></span>
              {{ $type }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="form-group">
        <span class="label label-default">Discount</span>
        <div class="badge-extra">
          <label class="v-checkbox">
            <input id="freeleech" name="freeleech" type="checkbox">
            <span></span>
            <i class="fa fa-star"></i>
            Freeleech
          </label>
        </div>

        <div class="badge-extra">
          <label class="v-checkbox">
            <input id="doubleupload" name="doubleupload" type="checkbox">
            <span></span>
            <i class="fa fa-diamond"></i>
            Double Upload
          </label>
        </div>

        <div class="badge-extra">
          <label class="v-checkbox">
            <input id="featured" name="featured" type="checkbox">
            <span></span>
            <i class="fa fa-certificate"></i>
            Featured Torrent
          </label>
        </div>
      </div>

      <input type="submit" value="Search"/>
    </form>

    <div class="block" id="stats">
      <strong>Stats:</strong>
      <span class="badge-extra text-bold">
        <i class="fa fa-file-o">
          {{ $count }} Torrents
        </i>
      </span>
      <span class="badge-extra">
        <i class="fa fa-smile-o">
          {{ $alive }} Alive
        </i>
      </span>
      <span class="badge-extra">
        <i class="fa fa-frown-o">
          {{ $dead }} Dead
        </i>
      </span>
    </div>

    <div class="block">
      <table class="torrents">
        <thead>
        <tr>
          <th class="category">Category</th>
          <th class="name">Name</th>
          <th class="time">Time</th>
          <th class="size">Size</th>
          <th class="completed">C</th>
          <th class="seeders">S</th>
          <th class="leechers">L</th>
        </tr>
        </thead>
        @foreach($torrents as $torrent)
          <tr>
            <td>
              <i class="fa torrent-icon {{ $torrent->category->icon }}"></i>
              {{ $torrent->type }}
              {{ $torrent->category->name }}
            </td>
            <td>
              <a class="view-torrent" href="/torrents/{{ $torrent->slug }}.{{ $torrent->id }}">
                {{ $torrent->name }}
              </a>
              @if ($torrent->tags->isNotEmpty())
                <div>
                  @foreach($torrent->tags as $tag)
                    <span class="tag badge-user">
                      <a href="/torrents/tags={{ $tag->name }}">{{ $tag->name }}</a>
                    </span>
                  @endforeach
                </div>
              @endif
              <div>
              </div>
              <div>
                @if(!$torrent->anon)
                  <span class="badge-extra text-bold">
                  <i class="fa fa-upload"></i>
                  By {{ $torrent->user->username }}
                </span>
                @endif
                <span class="badge-extra text-bold">
                  <i class="fa fa-heart"></i>
                  {{ $torrent->thanks->count() }}
                </span>
                @if($torrent->free)
                  <span class="badge-extra text-bold">
                    <i class="fa fa-star"></i>
                    Freeleech
                  </span>
                @endif
                @if($torrent->doubleup)
                  <span class="badge-extra text-bold">
                    <i class="fa fa-diamond"></i>
                    Double Upload
                  </span>
                @endif
                @if($torrent->featured)
                  <span class="badge-extra text-bold">
                    <i class="fa fa-certificate"></i>
                    Featured
                  </span>
                @endif
              </div>
            </td>
            <td>{{ $torrent->age() }}</td>
            <td>{{ \App\Helpers\StringHelper::formatBytes($torrent->size) }}</td>
            <td>{{ $torrent->times_completed }}</td>
            <td>{{ $torrent->seeders }}</td>
            <td>{{ $torrent->leechers }}</td>
          </tr>
        @endforeach
      </table>
    </div>
    {{ $torrents->links() }}
  </div>
@endsection