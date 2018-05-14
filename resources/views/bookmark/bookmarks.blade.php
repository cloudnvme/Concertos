@extends('layout.next')

@section('breadcrumb')
<li>
<a href="#" itemprop="url" class="l-breadcrumb-item-link">
<span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.bookmarks') }}</span>
</a>
</li>
@endsection

@section('content')
      <table class="table">
        <thead>
        <tr>
          <th>Category</th>
          <th>Name</th>
          <th>Size</th>
          <th>Seeders</th>
          <th>Leechers</th>
          <th>Age</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($myBookmarks as $b)
          <tr>
            <td>
              <i class="torrent-icon {{ $b->category->icon }}"></i>
              <a class="link" href="{{ route('torrents', ['type_' . $b->type->id => 'on']) }}">{{ $b->type->name }}</a>
              <a class="link" href="{{ route('torrents', ['category_' . $b->category->id => 'on']) }}">{{ $b->category->name }}</a>
            </td>
            <td>
              <a class="link" href="{{ route('torrent', ['id' => $b->id]) }}">
                {{ $b->name }}
              </a>
            </td>
            <td>
              {{ $b->getSize() }}
            </td>
            <td>
              {{ $b->seeders }}
            </td>
            <td>
              {{ $b->leechers }}
            </td>
            <td>
              {{ $b->created_at->diffForHumans() }}
            </td>
            <td>
              <a href="{{ route('unbookmark', ['id' => $b->id]) }}">
                <button class="btn">
                  <i class="fas fa-eraser"></i>
                  Delete
                </button>
              </a>
              <a href="{{ route('download', ['id' => $b->id]) }}">
                <button class="btn">
                  <i class="fa fa-download"></i>
                  Download
                </button>
              </a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
@endsection