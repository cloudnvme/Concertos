@extends('layout.default')

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
      <label class="form-group">
        <span class="col-sm-1 label label-default">Title</span>
        <input type="text" class="bar" name="title" placeholder="Title"/>
      </label>

      <div class="form-group">
        <span class="col-sm-1 label label-default">IDs</span>
        <input type="text" class="bar" name="imdb" placeholder="IMDB"/>
        <input type="text" class="bar" name="tmdb" placeholder="TMDB"/>
      </div>

      <div class="form-group">
        <span class="col-sm-1 label label-default">Categories</span>
        @foreach ($repository->categories() as $id => $category)
          <div class="category badge-extra">
            <label class="v-checkbox">
              <input type="checkbox" name="category_{{ $id }}"/>
              <span></span>
              {{ $category }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="form-group">
        <span class="col-sm-1 label label-default">Types</span>
        @foreach ($repository->types() as $id => $type)
          <div class="type badge-extra">
            <label class="v-checkbox">
              <input type="checkbox" name="type_{{ $id }}"/>
              <span></span>
              {{ $type }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="form-group">
        <span class="col-sm-1 label label-default">Extra</span>
        <label class="badge-extra v-checkbox">
          <input name="my_requests" type="checkbox">
          <span></span>
          <i class="fa fa-user"></i>
          My Requests
        </label>
        <label class="badge-extra v-checkbox">
          <input name="unfilled" type="checkbox">
          <span></span>
          <i class="fa fa-times-circle"></i>
          Unfilled
        </label>
        <label class="badge-extra v-checkbox">
          <input name="claimed" type="checkbox">
          <span></span>
          <i class="fa fa-suitcase"></i>
          Claimed
        </label>
        <label class="badge-extra v-checkbox">
          <input name="pending" type="checkbox">
          <span></span>
          <i class="fa fa-question-circle"></i>
          Pending
        </label>
        <label class="badge-extra v-checkbox">
          <input name="filled" type="checkbox">
          <span></span>
          <i class="fa fa-check-circle"></i>
          Filled
        </label>
      </div>

      <input type="submit" value="Search"/>
      <a href="{{ route('add_request') }}">
        <input type="button" class="v-button" value="Add request"/>
      </a>

      <div class="separator"></div>

      <div class="form-group">
        <span class="text-bold" id="stats">Stats:</span>
        <span class="badge-extra text-bold">{{ $num_req }} Requests</span>
      </div>
    </form>

    <table class="requests">
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
              <i class="fa torrent-icon {{ $request->category->icon }}"></i> {{ $request->type->name }} {{ $request->category->name }}
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
                Claimed
              @elseif ($request->filled_hash !== null && $request->approved_by === null)
                Pending
              @elseif ($request->filled_hash === null)
                Unfilled
              @else
                Filled
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection