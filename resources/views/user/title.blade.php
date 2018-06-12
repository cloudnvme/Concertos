<span class="text-bold" @if ($colored) style="color: {{ $user->roleColor() }}" @endif>
  <a class="link" href="{{ route('profile', ['id' => $user->id]) }}">
    </span><i class="{{ $user->roleIcon() }}"></i>
    {{ $user->roleName() }}
    {{ $user->username }}
  </a>
</span>