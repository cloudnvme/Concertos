<?php

namespace App;
use App\User;

class Policy
{
    static function isModerator(User $user)
    {
        return $user->group->is_modo;
    }
}