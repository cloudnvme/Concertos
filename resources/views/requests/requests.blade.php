@extends('layout.next')

@section('title')
<title>Requests - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
  </a>
</li>
@endsection

@section('content')
  <div class="container box">
    <form class="request-search" action="{{ route('requests') }}" method="get">
      <label class="flex mbox mbox--small-bottom">
        <span class="col col--small badge badge--centered mbox mbox--mini-right">Title</span>
        <input type="text" class="flex__expanded" name="title" placeholder="Title"/>
      </label>

      <div class="flex mbox mbox--small-bottom">
        <span class="col col--small badge badge--centered mbox mbox--mini-right">IDs</span>
        <input type="text" class="flex__expanded mbox mbox--mini-right" name="imdb" placeholder="IMDB"/>
        <input type="text" class="flex__expanded" name="tmdb" placeholder="TMDB"/>
      </div>

      <div class="flex flex--fluid mbox mbox--small-bottom">
        <span class="col col--small badge badge--centered mbox mbox--mini-right">Categories</span>
        @foreach ($repository->categories() as $id => $category)
          <div class="category badge badge--extra mbox mbox--mini-right">
            <label class="v-checkbox">
              <input type="checkbox" name="category_{{ $id }}"/>
              <span></span>
              {{ $category }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="flex flex--fluid">
        <span class="col col--small badge badge--centered mbox mbox--mini-right mbox--small-bottom">Types</span>
        @foreach ($repository->types() as $id => $type)
          <div class="type badge badge--extra mbox mbox--mini-right mbox--small-bottom">
            <label class="v-checkbox">
              <input type="checkbox" name="type_{{ $id }}"/>
              <span></span>
              {{ $type }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="flex">
        <span class="col col--small badge badge--centered mbox mbox--mini-right mbox--small-bottom">Extra</span>
        <label class="badge badge--extra v-checkbox mbox mbox--mini-right mbox--small-bottom">
          <input name="my_requests" type="checkbox">
          <span></span>
          <i class="fa fa-user"></i>
          My Requests
        </label>
        <label class="badge badge--extra v-checkbox mbox mbox--mini-right mbox--small-bottom">
          <input name="unfilled" type="checkbox">
          <span></span>
          <i class="fa fa-times-circle"></i>
          Unfilled
        </label>
        <label class="badge badge--extra v-checkbox mbox mbox--mini-right mbox--small-bottom">
          <input name="claimed" type="checkbox">
          <span></span>
          <i class="fa fa-suitcase"></i>
          Claimed
        </label>
        <label class="badge badge--extra v-checkbox mbox mbox--mini-right mbox--small-bottom">
          <input name="pending" type="checkbox">
          <span></span>
          <i class="fa fa-question-circle"></i>
          Pending
        </label>
        <label class="badge badge--extra v-checkbox mbox mbox--mini-right mbox--small-bottom">
          <input name="filled" type="checkbox">
          <span></span>
          <i class="fa fa-check-circle"></i>
          Filled
        </label>
      </div>

      <div class="buttons mbox mbox--bottom">
        <button type="submit" class="btn">
          <i class="fa fa-search"></i>
          Search
        </button>
        <a href="{{ route('add_request') }}">
          <button type="button" class="btn">
            <i class="far fa-file"></i>
            Add Request
          </button>
        </a>
      </div>

      <div class="stats mbox mbox--bottom">
        <span class="text-bold" id="stats">Stats:</span>
        <span class="badge badge--extra text-bold">{{ $num_req }} Requests</span>
      </div>
    </form>

    <table class="requests table">
      <thead>
        <tr>
          <th class="category">Category</th>
          <th class="title">Title</th>
          <th class="author">Author</th>
          <th class="votes">Votes</th>
          <th class="bounty">Bounty</th>
          <th class="age">Age</th>
          <th class="status">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($requests as $request)
          <tr>
            <td>
              <i class="fa torrent-icon {{ $request->category->icon }}"></i>
              <span>
                <a class="link" href="{{ route('requests', ['type_' . $request->type->id]) }}">{{ $request->type->name }}</a>
                <a class="link" href="{{ route('requests', ['category_' . $request->category->id]) }}">{{ $request->category->name }}</a>
              </span>
            </td>
            <td>
              <a class="view-torrent" href="{{ route('request', ['id' => $request->id]) }}">
                {{ $request->name }}
              </a>
            </td>
            <td>
              <a class="text-bold view-torrent" href="/{{ $request->user->username }}.{{ $request->user->id }}">
                {{ $request->user->username }}
              </a>
            </td>
            <td>
              {{ $request->votes }}
            </td>
            <td>
              {{ $request->bounty }}
            </td>
            <td>
              {{ $request->age() }}
            </td>
            <td>
              @if ($request->claimed !== null && $request->filled_hash === null)
                <a class="link" href="{{ route('requests', ['claimed' => 'on']) }}">Claimed</a>
              @elseif ($request->filled_hash !== null && $request->approved_by === null)
                <a class="link" href="{{ route('requests', ['pending' => 'on']) }}">Pending</a>
              @elseif ($request->filled_hash === null)
                <a class="link" href="{{ route('requests', ['unfilled' => 'on']) }}">Unfilled</a>
              @else
                <a class="link" href="{{ route('requests', ['filled' => 'on']) }}">Filled</a>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection