<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Events\SolutionCreated;
use App\Http\Requests\SolutionFormRequest;
use App\Http\Requests\SolutionMentorFormRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Http\Resources\SolutionResource;
use App\Services\SolutionService;
use Faker\Provider\Base;

class SolutionController extends Controller
{
    use ResponseUtils;
    private $service;
    public function __construct(SolutionService $service)
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
        $solutions = $this->service->index();
        return new BaseCollection($solutions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolutionFormRequest $request
     * @return BaseResource
     * @throws \App\Exceptions\BaseException
     */
    public function store(SolutionFormRequest $request)
    {
        $solution = $this->service->save($request->all());

        event(new SolutionCreated($solution));

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

    public function solutionsForMentor($id)
    {
        $solutions =$this->service->solutionsForMentor($id);
        return new BaseCollection($solutions);
    }

    public function solutionsForStartup($id)
    {
        $solutions =$this->service->solutionsForStartup($id);
        return new BaseResource($solutions);
    }

    public function getActiveSolution()
    {
        die('ddddd');
        $solutions =$this->service->getActiveSolution();
        return new BaseResource($solutions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolutionFormRequest $request
     * @param  int $id
     * @return BaseResource
     */
    public function update(SolutionFormRequest $request, $id)
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

    /**
     * @param SolutionMentorFormRequest $request
     * @param $mentor_id
     * @return BaseResource
     * @throws \App\Exceptions\BaseException
     */
    public function assignMentor(SolutionMentorFormRequest $request, $mentor_id)
    {
        $resp = $this->service->assignMentor($request->only('solution_id', 'mentor_ship_area_id'), $mentor_id, 'save');
        return new BaseResource($resp);
    }

    /**
     * @param SolutionMentorFormRequest $request
     * @param $mentor_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \App\Exceptions\BaseException
     */
    public function deleteMentor(SolutionMentorFormRequest $request, $mentor_id)
    {
        $this->service->assignMentor($request->only('solution_id', 'mentor_ship_area_id'), $mentor_id, 'delete');
        return $this->respondNoResult('action successful', 200);
    }
}
