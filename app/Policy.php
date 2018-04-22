<?php

namespace App;
use App\User;
use App\Torrent;
use Carbon\Carbon;

class Policy
{
    static function isModerator(User $user)
    {
        return $user->group->is_modo;
    }

    static function canEditTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || $torrent->user->id == $user->id;
    }

    static function canDeleteTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()));
    }
}