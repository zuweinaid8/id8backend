<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterFormRequest;
use App\Http\Resources\Users\UserResource;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    protected $userService;

    /**
     * RegisterController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterFormRequest $request
     * @return UserResource|\Illuminate\Http\Response
     */
    public function registerStartupUser(RegisterFormRequest $request)
    {
        //dd($request);
        $data = $request->only('email', 'password', 'name','name_of_fintech','year_founded','description','idea');
        $profile_picture = $request->input(['profile_picture_file_id']) != null? $request->input(['profile_picture_file_id']):null;
        $meta = $request->input(['meta']) != null? $request->input(['meta']):null;
        $data['profile_picture_file_id'] = $profile_picture;
        $data['meta'] = $meta;
        $user = $this->userService->saveUser($data);

        event(new Registered($user));

        return new UserResource($user);
    }

    /**
     * @param RegisterFormRequest $request
     * @return UserResource
     */
    public function registerAdmin(RegisterFormRequest $request)
    {
        $user = $this->userService->saveAdmin($request->only('email', 'password', 'name', 'meta'));
        return new UserResource($user);
    }

    /**
     * @param RegisterFormRequest $request
     * @return UserResource
     */
    public function registerInvestor(RegisterFormRequest $request)
    {
        $user = $this->userService->saveInvestor($request->only('email', 'password', 'name', 'meta'));
        return new UserResource($user);
    }

    /**
     * @param RegisterFormRequest $request
     * @return UserResource
     */
    public function registerMentor(RegisterFormRequest $request)
    {
        $data = $request->only('email', 'password', 'name', 'meta');
        $profile_picture = $request->input(['profile_picture_file_id']) != null? $request->input(['profile_picture_file_id']):null;
        $data['profile_picture_file_id'] = $profile_picture;
        $user = $this->userService->saveMentor($data);
        return new UserResource($user);
    }
}
