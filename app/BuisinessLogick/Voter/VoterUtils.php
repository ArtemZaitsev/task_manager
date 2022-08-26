<?php

namespace App\BuisinessLogick\Voter;

use Illuminate\Support\Facades\Auth;

class VoterUtils
{
    public static function userIsAdmin(): bool
    {
        $user = Auth::user();
        $permissions = $user->permissions;
        if (!isset($permissions['platform.index'])) {
            return false;
        }
        $permission = (int)$permissions['platform.index'];
        return $permission === 1;
    }
}
