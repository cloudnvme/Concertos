@extends('layout.next')

@section('title')
  <title>Request - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
  <li>
    <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
    </a>
  </li>
  <li>
    <a href="{{ route('request', ['id' => $torrentRequest->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.request-details') }}</span>
    </a>
  </li>
@endsection

@section('content')
  <div class="block mbox mbox--small-bottom">
    <div class="block__title">Info</div>
    <div class="block__content">
      <table class="table table--2">
        <tbody>
        <tr>
          <td class="table--2__title">Title</td>
          <td>
            {{ $torrentRequest->name }}
            <button class="btn"><i class="fa fa-eye"></i> {{ trans('request.report') }}</button>

            @if(\App\Policy::isModerator($user) && $torrentRequest->filled_hash != null)
              <a href="{{ route('resetRequest', ['id' => $torrentRequest->id]) }}">
                <button class="btn">
                  <i class="fa fa-undo"></i>
                  {{ trans('request.reset-request') }}
                </button>
              </a>
            @endif

            @if(\App\Policy::isModerator($user) || ($torrentRequest->user->id == $user->id && $torrentRequest->filled_hash == null))
              <a href="{{ route('edit_request', ['id' => $torrentRequest->id]) }}">
                <button class="btn">
                  <i class="fa fa-edit"></i>
                  {{ trans('request.edit-request') }}
                </button>
              </a>

            <a href="{{ route('deleteRequest', ['id' => $torrentRequest->id]) }}">
              <button class="btn">
                <i class="fa fa-trash"></i> {{ trans('common.delete') }}
              </button>
            </a>
            @endif
          </td>
        </tr>
        <tr>
          <td class="table--2__title">Category</td>
          <td>{{ $torrentRequest->category->name }}</td>
        </tr>
        <tr>
          <td class="table--2__title">Type</td>
          <td>{{ $torrentRequest->type->name }}</td>
        </tr>
        <tr>
          <td class="table--2__title">Description</td>
          <td>{!! $torrentRequest->getDescriptionHtml() !!}</td>
        </tr>
        <tr>
          <td class="table--2__title">Requested by</td>
          <td>{!! $torrentRequest->user->getFullName() !!}</td>
        </tr>

        @if ($torrentRequest->filled_hash === null)
          <tr>
            <td class="table--2__title">Vote up</td>
            <td>
              <form action="{{ route('add_votes', ['id' => $torrentRequest->id]) }}" method="post">
                @csrf
                <input type="hidden" name="request_id" value="{{ $torrentRequest->id }}"/>
                <input type="text" name="bonus_value"/>
                <button type="submit" class="btn"><i class="fa fa-thumbs-up"></i> {{ trans('request.vote') }}</button>
              </form>
            </td>
          </tr>
        @endif

        @if($torrentRequest->filled_hash == null)
        <tr>
          <td class="table--2__title">Fullfill</td>
          <td>
              @if($torrentRequest->claimed == 0 || ($torrentRequest->claimed == 1 && $torrentRequestClaim->username == $user->username || \App\Policy::isModerator($user)))
                <form action="{{ route('fill_request', ['id' => $torrentRequest->id]) }}" method="post">
                  @csrf
                  <input type="hidden" name="request_id" value="{{ $torrentRequest->id }}"/>
                  <input type="text" name="info_hash"/>
                  <button type="submit" class="btn">
                    <i class="fa fa-link"></i>
                    {{ trans('request.fulfill') }}
                  </button>
                </form>
              @endif
          </td>
        </tr>
        @endif

        <tr>
          <td class="table--2__title">Claim this Request</td>
          <td>
            @if ($torrentRequest->claimed == null && $torrentRequest->filled_hash == null)
              <button class="btn btn-md btn-success btn-vote-request" data-toggle="modal" data-target="#claim">
                <i class="fa fa-suitcase"></i> {{ trans('request.claim') }}
              </button>
            @elseif ($torrentRequest->filled_hash != null && $torrentRequest->approved_by == null)
              <button class="btn btn-xs btn-info" disabled>
                <i class="fa fa-question-circle"></i>
                {{ trans('request.pending') }}
              </button>


              @if ($torrentRequest->user_id === auth()->user()->id || \App\Policy::isModerator(auth()->user()))
                <a href="{{ route('approveRequest', ['id' => $torrentRequest->id]) }}">
                  <button class="btn">
                    {{ trans('request.approve') }}
                  </button>
                </a>

                <a href="{{ route('rejectRequest', ['id' => $torrentRequest->id]) }}">
                  <button class="btn">
                    {{ trans('request.reject') }}
                  </button>
                </a>
              @endif
            @elseif ($torrentRequest->filled_hash != null)
              <button class="btn btn-xs btn-success" disabled>
                <i class="fa fa-check-square-o"></i>
                {{ trans('request.filled') }}
              </button>
            @else
              @if($torrentRequestClaim->anon == 0)
                <span class="badge-user">{{ $torrentRequestClaim->username }}</span>
                @if(\App\Policy::isModerator($user) || $torrentRequestClaim->username == $user->username)
                  <a href="{{ route('unclaimRequest', ['id' => $torrentRequest->id]) }}" class="btn btn-xs btn-danger">
                    <span class="icon">
                      <i class="fa fa-times"></i>
                      {{ trans('request.unclaim') }}
                    </span>
                  </a>
                @endif
              @else
                <span class="badge-user">{{ strtoupper(trans('common.anonymous')) }}</span>
                @if(\App\Policy::isModerator($user) || $torrentRequestClaim->username == $user->username)
                  <a href="{{ route('unclaimRequest', ['id' => $torrentRequest->id]) }}" class="btn btn-xs btn-danger">
                    <span class="icon">
                      <i class="fa fa-times"></i>
                      {{ trans('request.unclaim') }}
                    </span>
                  </a>
                @endif
              @endif
            @endif
          </td>
        </tr>

        @if ($torrentRequest->filled_hash != null && $torrentRequest->approved_by !== null)
          <tr>
            <td class="table--2__title">Torrent</td>
            <td>
              <a class="link" href="{{ route('torrent', ['id' => $torrentRequest->torrent->id]) }}">
                {{ $torrentRequest->torrent->name }}
              </a>
            </td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>

  @if ($voters->isNotEmpty())
    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Voters</div>
      <div class="block__content">
        <table class="table">
          <thead>
          <tr>
            <th>Voter</th>
            <th>BONs</th>
            <th>Age</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($voters as $voter)
            <tr>
              <td class="col col--big">{!! $voter->user->getFullName() !!}</td>
              <td>{{ $voter->seedbonus }}</td>
              <td>{{ $voter->created_at->diffForHumans() }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  @if ($comments->isNotEmpty())
    <div class="block mbox mbox--small-bottom">
      <div class="block__title">Comments</div>
      <div class="block__content">
        {!! $torrentRequest->renderComments() !!}
      </div>
    </div>
  @endif

  <form class="comment-editor block" action="{{ route('comment_request', ['id' => $torrentRequest->id]) }}">
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
@endsection