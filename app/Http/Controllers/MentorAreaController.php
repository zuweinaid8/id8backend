<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Http\Requests\MentorShipAreaFormRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Services\MentorAreaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MentorAreaController extends Controller
{
    use ResponseUtils;

    protected $mentorAreaService;
    public function __construct(MentorAreaService $mentorAreaService)
    {
        $this->mentorAreaService = $mentorAreaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return BaseCollection
     */
    public function index()
    {
        //
        $mentor = $this->mentorAreaService->getAllMentor_Areas();
        return new BaseCollection($mentor);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param MentorShipAreaFormRequest $request
     * @return BaseResource
     */
    public function store(MentorShipAreaFormRequest $request)
    {
        $mentor = $this->mentorAreaService->save($request);
        return new BaseResource($mentor);
    }

    /**
     * @param MentorShipAreaFormRequest $request
     * @param $mentor_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function assignArea(MentorShipAreaFormRequest $request, $mentor_id)
    {
        $this->mentorAreaService->toggle($request->input('mentor_ship_area_id'), $mentor_id);
        return $this->respondNoResult('action successful', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return BaseResource
     */
    public function show($id)
    {
        return new BaseResource($this->mentorAreaService->show($id));
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
        $startup = $this->mentorAreaService->update($id, $request->all());
        return new BaseResource($startup);
    }

    public function delete(Request $request, $id)
    {
        $this->mentorAreaService->delete($id);
        return $this->respondNoResult('Deleted', 200);
    }
}
