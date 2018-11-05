<?php

namespace App\Http\Middleware\Auth;

use App\Commons\ResponseUtils;
use App\Commons\Utils;
use App\Exceptions\BaseException;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class RoleAuthenticator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param mixed ...$roles
     * @return mixed
     * @throws AuthorizationException
     * @throws BaseException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (in_array(Utils::getLoggedInUser(request()->user()->id)->type, $roles)) {
            if (Utils::getLoggedInUser($request->user()->id)->type == 'mentor' && Utils::getLoggedInUser($request->user()->id)->meta->is_initial && $request->path() == 'api/set_password/mentor') {
                return $next($request);
            } elseif (Utils::getLoggedInUser($request->user()->id)->type == 'mentor' && $request->user()->meta->is_initial) {
                throw new BaseException("You have to change to new password");
            } else {
                return $next($request);
            }
        } else {
            throw new AuthorizationException("Unauthorized");
        }
    }
}
