<?php

namespace App;
use App\User;
use App\Torrent;
use Carbon\Carbon;

class Policy
{
    public static function isModerator(User $user)
    {
        return $user->group->is_modo;
    }

    public static function isAdmin(User $user)
    {
        return $user->group->is_admin;
    }

    public static function isInternal(User $user)
    {
        return $user->group->is_internal;
    }

    public static function isFreeleech(User $user)
    {
        return $user->group->is_freeleech;
    }

    public static function isImmune(User $user)
    {
        return $user->group->is_immune;
    }

    public static function hasPrivacy(User $user)
    {
        return $user->group->has_privacy;
    }

    public static function isTrusted(User $user)
    {
        return $user->group->is_trusted;
    }

    public static function canEditTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || $torrent->user->id == $user->id;
    }

    public static function canDeleteTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()));
    }
}