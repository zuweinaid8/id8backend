<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseCollection;
use App\Services\SolutionStageService;
use App\Services\SolutionStatusService;
use Illuminate\Http\Request;

class SolutionStatusController extends Controller
{
    private $service;
    public function __construct(SolutionStatusService $service)
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
        $stages = $this->service->getAllSolution_Status();
        return new BaseCollection($stages);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
