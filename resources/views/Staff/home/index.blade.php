@extends('layout.next')

@section('title')
	<title>Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Moderation Dashboard">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
    </a>
</li>
@endsection

@section('content')
	<div class="block mbox mbox--small-bottom">
		<div class="block__title">Stats</div>
		<div class="block__content">
			<table class="table site-stats">
				<tbody>
				<tr>
					<td>Total torrents</td>
					<td>{{ $num_torrent }}</td>
				</tr>

				<tr>
					<td>Peers</td>
					<td>{{ $peers }}</td>
				</tr>

				<tr>
					<td>Seedboxes</td>
					<td>{{ $seedboxes }}</td>
				</tr>

				<tr>
					<td>Users</td>
					<td>{{ $num_user }}</td>
				</tr>

				<tr>
					<td>Reports</td>
					<td>{{ $reports }}</td>
				</tr>

				<tr>
					<td>Polls</td>
					<td>{{ $pollCount }}</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="block">
		<div class="block__title">General tools</div>
		<div class="block__content flex flex--column">
			<a href="{{ route('staff_article_index') }}"><i class="far fa-newspaper"></i> {{ trans('staff.articles') }}</a>
			@if(\App\Policy::isAdministrator(auth()->user()))
				<a href="{{ route('staff_forum_index') }}"><i class="fab fa-wpforms"></i> {{ trans('staff.forums') }}</a>
			@endif
			<a href="{{ route('staff_page_index') }}"><i class="fa fa-file"></i> {{ trans('staff.pages') }}</a>
			<a href="{{ route('getPolls') }}"><i class="fas fa-chart-pie"></i> {{ trans('staff.polls') }}</a>
		</div>
	</div>

	<div class="block">
		<div class="block__title">Torrent tools</div>
		<div class="block__content flex flex--column">
			<a href="{{ route('staff_torrent_index') }}"><i class="fas fa-file"></i> {{ trans('staff.torrents') }}</a>
			<a href="{{ route('staff_category_index') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-categories') }}</a>
			<a href="{{ route('staff_type_index') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-types') }}</a>
			<a href="{{ route('getCatalog') }}"><i class="fa fa-book"></i> {{ trans('staff.catalog-groups') }}</a>
			<a href="{{ route('getCatalogTorrent') }}"><i class="fa fa-book"></i> {{ trans('staff.catalog-torrents') }}</a>
			<a href="{{ route('flush') }}"><i class="fab fa-snapchat-ghost"></i> {{ trans('staff.flush-ghost-peers') }}</a>
			<a href="{{ route('moderation') }}"><i class="fa fa-columns"></i> {{ trans('staff.torrent-moderation') }}</a>
		</div>
	</div>

	<div class="block">
		<div class="block__title">User tools</div>
		<div class="block__content flex flex--column">
			<a href="{{ route('user_search') }}"><i class="fa fa-users"></i> {{ trans('staff.user-search') }}</a>
			<a href="{{ route('getNotes') }}"><i class="fa fa-comment"></i> {{ trans('staff.user-notes') }}</a>
			<a href="{{ route('systemGift') }}"><i class="fa fa-gift"></i> {{ trans('staff.user-gifting') }}</a>
			<a href="{{ route('massPM') }}"><i class="fas fa-share-square"></i> {{ trans('staff.mass-pm') }}</a>
		</div>
	</div>

	<div class="block">
		<div class="block__title">Logs</div>
		<div class="block__content flex flex--column">
			<a href="{{ route('activityLog') }}"><i class="fa fa-file"></i> {{ trans('staff.activity-log') }}</a>
			<a href="{{ route('getBans') }}"><i class="fa fa-file"></i> {{ trans('staff.bans-log') }}</a>
			<a href="{{ route('getFailedAttemps') }}"><i class="fa fa-file"></i> {{ trans('staff.failed-login-log') }}</a>
			<a href="{{ route('getInvites') }}"><i class="fa fa-file"></i> {{ trans('staff.invites-log') }}</a>
			@if(\App\Policy::isAdministrator(auth()->user()))
				<a href="/staff/log-viewer"><i class="fa fa-file"></i> {{ trans('staff.laravel-log') }}</a>
			@endif
			<a href="{{ route('getReports') }}"><i class="fa fa-file"></i> {{ trans('staff.reports-log') }}</a>
			<a href="{{ route('getWarnings') }}"><i class="fa fa-file"></i> {{ trans('staff.warnings-log') }}</a>
		</div>
	</div>
@endsection