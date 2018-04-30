@extends('layout.next')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li class="active">
    <a href="{{ route('create', array('id' => auth()->user()->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.send') }} {{ trans('pm.message') }}</span>
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
    <div class="block__title">Compose</div>
    <div class="block__content">
      <form role="form" method="POST" action="{{ route('send-pm') }}">
        {{ csrf_field() }}
        <div class="flex mbox mbox--mini-bottom">
          <div class="badge col col--small mbox mbox--mini-right">To</div>
          <input name="receiver" class="flex__expanded" type="text" required/>
        </div>

        <div class="flex mbox mbox--small-bottom">
          <div class="badge col col--small mbox mbox--mini-right">Subject</div>
          <input name="subject" class="flex__expanded" type="text" required/>
        </div>

        <textarea id="message" name="message" class="textarea textarea--vertical" rows=20></textarea>

        <button class="btn col col--medium">
          <i class="far fa-paper-plane"></i>
          Send
        </button>
      </form>
    </div>
  </div>
@endsection