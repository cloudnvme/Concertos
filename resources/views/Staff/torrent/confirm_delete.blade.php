@extends('layout.default')

@section('title')
  <title>Confirm Removal of Torrent</title>
@endsection

@section('stylesheets')
  <link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('content')
  <div class="container box" id="confirm-delete">
    <form action="/torrents/delete" method="post">
      <h1>Are you sure you want to delete this torrent?</h1>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="form-group">
        <span class="col-sm-1 label label-default">ID</span>
        <input type="text" class="bar" name="id" value="{{ $torrent->id }}"/>
      </div>
      <div class="form-group">
        <span class="col-sm-1 label label-default">Name</span>
        <input type="text" class="bar" name="name" value="{{ $torrent->name }}"/>
      </div>
      <div class="form-group">
        <span class="col-sm-1 label label-default">Slug</span>
        <input type="text" class="bar" name="slug" value="{{ $torrent->slug }}"/>
      </div>
      <div class="form-group">
        <span class="col-sm-1 label label-default">Message</span>
        <input type="text" class="bar" name="message" placeholder="Message"/>
      </div>
      <input type="submit" value="Delete!"/>
    </form>
  </div>
@endsection