<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>{{ trans('blocks.users-online') }}<small> ({{ trans('blocks.active-in-last') }} 15 min)</small></h4></div>
    <div class="panel-body">
      @foreach($user as $u)
      @if($u->isOnline())
      @if($u->hidden == 1)
      <span class="badge-user text-orange text-bold" style="margin-bottom: 10px;">{{ strtoupper(trans('common.hidden')) }} @if(\App\Policy::isModerator(auth()->user()))<a href="{{ route('profile', array('id' => $u->id)) }}"> ({{ $u->username }} @if($u->countWarnings() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>@endif)</a>@endif</span>
      @else
      <a href="{{ route('profile', array('id' => $u->id)) }}"><span class="badge-user text-bold" style="color:{{ $u->roleColor() }}; background-image:{{ $u->roleEffect() }}; margin-bottom: 10px;"><i class="{{ $u->roleIcon() }}" data-toggle="tooltip" title="" data-original-title="{{ $u->roleName() }}"></i> {{ $u->username }} @if($u->countWarnings() > 0)<i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="{{ trans('common.active-warning') }}"></i>
      @endif
      </span></a>
      @endif
      @endif
      @endforeach
    </div>
  </div>
</div>
