@extends('layout.default')

@section('title')
	<title>Edit Forums - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Edit Forums - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Forums</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
	<h2>Edit: {{ $forum->name }}</h2>

		{{ Form::open(array('route' => array('staff_forum_edit', 'id' => $forum->id))) }}
			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" name="title" class="form-control" value="{{ $forum->name }}">
			</div>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" cols="30" rows="10">{{ $forum->description }}</textarea>
			</div>

			<div class="form-group">
				<label for="parent_id">Parent forum</label>
				<select name="parent_id" class="form-control">
					<!-- Selectionne le forum parent par defaut -->
					@if($forum->getCategory() != null)
						<option value="{{ $forum->parent_id }}" selected>{{ $forum->getCategory()->name }} (Default)</option>
					@endif<!-- /Selectionne le forum parent par defaut -->
					@foreach($categories as $c)
						<option value="{{ $c->id }}">{{ $c->name }}</option>
						{{-- @foreach($c->getForumsInCategory() as $f)
							<option value="{{ $f->id }}">---- {{ $f->name }}</option>
						@endforeach --}}
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="position">Position</label>
				<input type="text" name="position" class="form-control" placeholder="The position number" value="{{ $forum->position }}">
			</div>

			<button type="submit" class="btn btn-default">Save Forum</button>
		{{ Form::close() }}
</div>
@endsection
