@extends('layout.next')

@section('breadcrumb')
<li class="active">
    <a href="{{ route('outbox', array('id' => auth()->user()->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.outbox') }}</span>
    </a>
</li>
@endsection

@section('content')
  <div class="mbox mbox--small-bottom">
    <a href="{{ route('create', array('id' => auth()->user()->id)) }}">
      <button class="btn">
        <i class="fa fa-pencil-alt"></i>
        {{ trans('pm.new') }}
      </button>
    </a>
    <a href="{{ route('inbox', array('id' => auth()->user()->id)) }}">
      <button class="btn">
        <i class="fas fa-cloud-download-alt"></i>
        {{ trans('pm.inbox') }}
      </button>
    </a>
    <a href="{{ route('outbox', array('id' => auth()->user()->id)) }}">
      <button class="btn">
        <i class="fas fa-cloud-upload-alt"></i>
        {{ trans('pm.outbox') }}
      </button>
    </a>
  </div>

  <div class="block">
    <div class="block__title">Messages</div>
    <div class="block__content">
      <table class="table table--bordered">
        <thead>
        <tr>
          <th>
            <label class="v-checkbox">
              <input id="check" type="checkbox" name="pm_id">
              <span></span>
            </label>
          </th>
          <th>To</th>
          <th>{{ trans('pm.subject') }}</th>
          <th>Sent at</th>
          <th>{{ trans('pm.read') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pms as $p)
          {{ Form::hidden('invisible', 'id', array('id' => 'id')) }}
          <tr>
            <td>
              <label class="v-checkbox">
                <input id="check" type="checkbox" name="pm_id">
                <span></span>
              </label>
            </td>
            <td>
              <a class="link" href="{{ route('profile', ['id' => $p->receiver->id]) }}" title="">{{ $p->receiver->username}}</a>
            </td>
            <td>
              <a class="link" href="{{ route('message', ['id' => $user->id , 'pmid' => $p->id]) }}">{{ $p->subject }}</a>
            </td>
            <td>
              {{ $p->created_at->diffForHumans() }}
            </td>

            @if ($p->read == 0)
              <td>
                <span class='label label--danger'>{{ trans('pm.unread') }}</span>
              </td>
            @else ($p->read >= 1)
              <td>
                <span class='label label--success'>{{ trans('pm.read') }}</span>
              </td>
            @endif
          </tr>
          {{ Form::close() }}
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection