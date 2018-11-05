<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Http\Requests\HurdleFormRequest;
use App\Http\Requests\SolutionFormRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Services\HurdleService;
use Carbon\Carbon;

class HurdleController extends Controller
{
    use ResponseUtils;
    private $service;
    public function __construct(HurdleService $service)
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
        $hurdles = $this->service->index();
        return new BaseCollection($hurdles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param HurdleFormRequest $request
     * @return BaseResource
     * @throws \App\Exceptions\BaseException
     */
    public function store(HurdleFormRequest $request)
    {
        $solution = $this->service->save($request->all());
        return new BaseResource($solution);
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
     * @return BaseResource
     */
    public function showActive()
    {
        return new BaseResource($this->service->showActive(Carbon::now()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HurdleFormRequest $request
     * @param  int $id
     * @return BaseResource
     */
    public function update(HurdleFormRequest $request, $id)
    {
        $startup = $this->service->update($id, $request->all());
        return new BaseResource($startup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->respondNoResult('Deleted', 200);
    }
}
