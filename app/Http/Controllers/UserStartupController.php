<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Services\UserStartupService;
use Illuminate\Http\Request;

class UserStartupController extends Controller
{
    use ResponseUtils;
    private $service;
    public function __construct(UserStartupService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return BaseCollection
     */
    public function index()
    {
        return new BaseCollection($this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return BaseResource
     */
    public function show($id)
    {
        return new BaseResource($this->service->show($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return BaseResource
     */
    public function update(Request $request, $id)
    {
        $startup = $this->service->update($id, $request->all());
        return new BaseResource($startup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->respondNoResult('Deleted', 200);
    }
}
