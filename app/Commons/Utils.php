<?php

namespace App\Commons;

use App\User;

class Utils
{
    public static function getConfig($key)
    {
        return config($key);
    }

    public static function getLoggedInUser($id)
    {
        return User::query()->findOrFail($id);
    }
}
