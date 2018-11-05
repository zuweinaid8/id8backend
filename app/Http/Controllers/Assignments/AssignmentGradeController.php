<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Requests\Assignments\AssignmentGradeRequest;
use App\Http\Resources\BaseResource;
use App\Models\Assignments\Assignment;
use App\Models\Assignments\AssignmentGrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignmentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Assignment $assignment
     * @return mixed
     */
    public function index(Assignment $assignment)
    {
        $grades = $assignment->grades()
            ->with([
                'graded_by'
            ])
            ->paginate();

        return BaseResource::collection($grades);
    }

    public function show(Assignment $assignment, $id)
    {
        $grade = $assignment->grades()
            ->with([
                'graded_by'
            ])
            ->findOrFail($id);

        return new BaseResource($grade);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Assignment $assignment
     * @param AssignmentGradeRequest $request
     * @return mixed
     */
    public function store(Assignment $assignment, AssignmentGradeRequest $request)
    {
        $grade = $assignment->grades()->create($request->all());

        $grade->load('graded_by');
        $grade->fresh();

        return new BaseResource($grade);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Assignment $assignment
     * @param $grade_id
     * @param AssignmentGradeRequest $request
     * @return mixed
     */
    public function update(Assignment $assignment, $grade_id, AssignmentGradeRequest $request)
    {
        $grade = $assignment->grades()->findOrFail($grade_id);
        $grade->update($request->all());
        $grade->save();

        $grade->load('graded_by');
        $grade->fresh();

        return new BaseResource($grade);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Assignment $assignment
     * @param $grade_id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Assignment $assignment, $grade_id)
    {
        $grade = $assignment->grades()->findOrFail($grade_id);

        // Check user has permission to delete grade
        $this->authorize('delete', $grade);

        $grade->delete();

        return $this->respondNoContent();
    }
}
