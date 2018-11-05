<?php

namespace App\Models;

use App\User;

class Investor extends User
{
    protected $hidden = ['pivot','password'];
}
