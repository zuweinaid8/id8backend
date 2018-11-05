<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Commons\Utils;
use App\Http\Requests\StartupFormRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Services\StartupService;

class StartupController extends Controller
{
    use ResponseUtils;

    protected $startupService;

    public function __construct(StartupService $startupService)
    {
        $this->startupService = $startupService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        if (Utils::getLoggedInUser(request()->user()->id)->type=='admin') {
            return new BaseCollection($this->startupService->index());
        } else {
            $startup = $this->startupService->ownerStartup();

            if(is_null($startup)) {
                return $this->respond('User has no startups!', null, 200);
            }

            return new BaseResource($startup);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StartupFormRequest $request
     * @return BaseResource
     */
    public function store(StartupFormRequest $request)
    {
        $startup = $this->startupService->save($request);
        return new BaseResource($startup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StartupFormRequest $request
     * @param  int $id
     * @return BaseResource
     */
    public function update(StartupFormRequest $request, $id)
    {
        $startup = $this->startupService->update($id, $request);
        return new BaseResource($startup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->startupService->delete($id);
        return $this->respondNoResult('Deleted', 200);
    }

    /**
     * @param $id
     * @return BaseResource
     */
    public function show($id)
    {
        return new BaseResource($this->startupService->show($id));
    }
}
