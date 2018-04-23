<?php

namespace App;
use App\User;
use App\Torrent;
use App\Topic;
use App\Forum;
use Carbon\Carbon;

class Policy
{
    public static function isAdministrator(User $user)
    {
        return $user->hasRole('Administrator') || $user->hasRole('Owner') || $user->hasRole('Bot');
    }

    public static function isModerator(User $user)
    {
        return self::isAdministrator($user) || $user->hasRole('Moderator');
    }

    public static function isInternal(User $user)
    {
        return $user->hasRole('Internal');
    }

    public static function isTrusted(User $user)
    {
        return $user->hasRole('Trustee') || $user->hasRole('Uploader');
    }

    public static function isFreeleech(User $user)
    {
        return self::isTrusted($user);
    }

    public static function isImmune(User $user)
    {
        return self::isModerator($user);
    }

    public static function hasPrivacy(User $user)
    {
        return self::isAdministrator($user);
    }

    public static function isBanned(User $user)
    {
        return $user->hasRole('Banned');
    }

    public static function isValidating(User $user)
    {
        return $user->hasRole('Validating');
    }

    public static function canEditTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || $torrent->user->id == $user->id;
    }

    public static function canDeleteTorrent(User $user, Torrent $torrent)
    {
        return self::isModerator($user) || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()));
    }

    public static function canReadTopic(User $user, Topic $topics)
    {
        return true;
    }

    public static function canViewForum(User $user, Forum $forum)
    {
        return true;
    }

    public static function canCreateTopic(User $user, Forum $forum)
    {
        return true;
    }

    public static function canReplyTopic(User $user, Topic $topic)
    {
        return $topic->state != "close" || self::isModerator($user);
    }
}