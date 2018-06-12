@extends('layout.next')

@section('breadcrumb')
  <li>
    <a href="{{ route('page', ['id' => $page->id]) }}" itemprop="url"
       class="l-breadcrumb-item-link">
      <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $page->name }}</span>
    </a>
  </li>
@endsection

@section('content')
  <h1 class="title">{{ $page->name }}</h1>
  <article class="page-content">
    {!! $page->getContentHtml() !!}
  </article>
@endsection