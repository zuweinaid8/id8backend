<?php

namespace App\Http\Middleware\Auth;

use App\Commons\ResponseUtils;
use App\Commons\Utils;
use App\Exceptions\AuthPasswordChangeException;
use App\Exceptions\BaseException;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class MentorDefaultPasswordChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthPasswordChangeException
     */
    public function handle($request, Closure $next)
    {
        if ($request->path() == 'api/user/password_reset') {
            return $next($request);
        } else {
            if (Utils::getLoggedInUser($request->user()->id)->type == 'mentor' && $request->user()->meta->is_initial && $request->path() == 'api/set_password/mentor') {
                return $next($request);
            } elseif (Utils::getLoggedInUser($request->user()->id)->type == 'mentor' && $request->user()->meta->is_initial) {
                throw new AuthPasswordChangeException("You have to change to new password");
            } else {
                return $next($request);
            }
        }
    }
}
