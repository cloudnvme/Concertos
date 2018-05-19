@extends('layout.next')

@section('stylesheets')
  <link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
  <li class="active">
    <a href="{{ route('message', array('id' => auth()->user()->id, 'pmid' => $pm->id)) }}">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.message') }}</span>
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

  <div class="block mbox mbox--small-bottom">
    <div class="block__title">Header</div>
    <div class="block__content">
      <div>
        <span class="col col--block col--small text-bold">From:</span> {!! $pm->sender->getFullName() !!}
      </div>

      <div>
        <span class="col col--block col--small text-bold">Subject:</span> {{ $pm->subject }}
      </div>
    </div>
  </div>

  <div class="block mbox mbox--small-bottom">
    <div class="block__title">Message</div>
    <div class="block__content">
      {!! $pm->getMessageHtml() !!}
    </div>
  </div>

  <div class="block">
    <div class="block__title">Reply</div>
    <div class="block__content">
      <form role="form" method="POST" action="{{ route('reply-pm',['pmid' => $pm->id]) }}">
        {{ csrf_field() }}
        <textarea name="message" class="textarea textarea--vertical" cols="30" rows="10"></textarea>
        <button type="submit" class="btn">
          <i class="fas fa-reply"></i>
          {{ trans('pm.reply') }}
        </button>
      </form>
    </div>
  </div>
@endsection