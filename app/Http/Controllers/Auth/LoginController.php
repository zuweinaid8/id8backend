<?php

namespace App\Http\Controllers\Auth;

use App\Commons\ResponseUtils;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class LoginController extends AccessTokenController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ValidatesRequests,ResponseUtils;

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        // Add OAuth password client credentials to request
        $request = $this->addClientCredentials($request);

        //convert Laravel Request (Symfony Request) to PSR-7
        $psr7Factory = new DiactorosFactory();
        $psrRequest = $psr7Factory->createRequest($request);

        //generate access token
        $tokenResponse = parent::issueToken($psrRequest);
        $tokenJson = json_decode($tokenResponse->content());

        if (isset($tokenJson->access_token)) {
            $tokenJson->code = 200;
            $tokenJson->message = 'success';
        } else {
            $tokenJson->code = 500;
            $tokenJson->message = 'error';
        }
        return Response::json($tokenJson);
    }

    public function logout(Request $request)
    {
        // Taken from: https://laracasts.com/discuss/channels/laravel/laravel-53-passport-password-grant-logout
        $accessToken = $request->user('api')->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response([], \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function addClientCredentials(Request $request)
    {
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->input('email', null),
            'password' => $request->input('password', null),
            'scope' => null,
        ]);

        return $request;
    }
}
