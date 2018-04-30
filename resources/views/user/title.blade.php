</span><i class="{{ $user->roleIcon() }}"></i>
{{ $user->roleName() }}
<a class="link" href="{{ route('profile', ['id' => $user->id]) }}">{{ $user->username }}</a>
