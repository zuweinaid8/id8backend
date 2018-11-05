<?php

namespace App\Http\Controllers\Auth;

use App\Commons\ResponseUtils;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\UserPasswordResetFormRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\Mentor;
use App\Services\UserService;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    use ResponseUtils;
    private $service;
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Display a listing of the resource.
     * @return BaseCollection
     */
    public function index()
    {
        $type=request()->query('type');
        $users=$this->service->index($type);
        return new BaseCollection($users);
    }

    /**
     * @param RegisterFormRequest $request
     * @return BaseResource
     */
    public function update(RegisterFormRequest $request)
    {
        $users=$this->service->update(request()->input());
        return new BaseResource($users);
    }

    /**
     * @param UserPasswordResetFormRequest $request
     * @return BaseResource
     * @throws \App\Exceptions\BaseException
     */
    public function resetPassword(UserPasswordResetFormRequest $request)
    {
        $this->service->resetPassword($request->input('password'));
        return $this->respondNoResult('password reset successfully', 200);
    }

    public function getLoggedInUser()
    {

        echo "<pre/>";
        print_r(request()->user());
        die('dddddddd');
        if (request()->user()->type == 'mentor') {
            $user = Mentor::query()->with('mentor_areas')
                ->where('id', request()->user()->id)
                ->first();
        }
        else {
            $user =request()->user();
        }

        $user->load([
            'profile_photo_file',
            'cover_photo_file',
        ]);
           

           
        return new BaseResource($user);
    }
}
