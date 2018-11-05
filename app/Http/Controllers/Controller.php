<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, ResponseUtils;

    use AuthorizesRequests {
        authorize as protected traitAuthorize;
    }

    public function authorize($ability, $arguments = [], $error_message = null)
    {
        try {
            $this->traitAuthorize($ability, $arguments);
        } catch (AuthorizationException $e) {
            if (isset($error_message)) {
                throw new AuthorizationException($error_message);
            }

            throw $e;
        }
    }
}
