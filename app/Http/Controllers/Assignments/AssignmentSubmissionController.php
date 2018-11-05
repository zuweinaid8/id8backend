<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignments\AssignmentSubmissionRequest;
use App\Http\Resources\BaseResource;
use App\Models\Assignments\Assignment;
use App\Models\Assignments\AssignmentSubmission;
use Illuminate\Support\Facades\DB;

class AssignmentSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Assignment $assignment
     * @return mixed
     */
    public function index(Assignment $assignment)
    {
        $submissions = $assignment->submissions()
            ->orderBy('id', 'desc')
            ->with('files')->paginate();

        return BaseResource::collection($submissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Assignment $assignment
     * @param AssignmentSubmissionRequest $request
     * @return mixed
     */
    public function store(Assignment $assignment, AssignmentSubmissionRequest $request)
    {
        /** @var AssignmentSubmission $submission */
        $submission = $assignment->submissions()->create($request->all());

        // Attach file ids to submission
        $file_ids = collect($request->input('files'));
        $submission->files()->sync($file_ids->pluck('id'));

        // Reload the created model
        $submission->load([
            'files',
            'created_by',
            'updated_by'
        ]);
        $submission->fresh();

        return new BaseResource($submission);
    }

    /**
     * Display the specified resource.
     *
     * @param Assignment $assignment
     * @param  int $id
     * @return mixed
     */
    public function show(Assignment $assignment, $id)
    {
        $submission = $assignment->submissions()
            ->with('files')->findOrFail($id);

        return new BaseResource($submission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Assignment $assignment
     * @param AssignmentSubmissionRequest $request
     * @param  int $id
     * @return mixed
     */
    public function update(Assignment $assignment, AssignmentSubmissionRequest $request, $id)
    {
        $submission = $assignment->submissions()->findOrFail($id);

        $submission->update($request->all());
        $submission->save();

        // Attach file ids to submission
        $file_ids = collect($request->input('files'));
        $submission->files()->sync($file_ids->pluck('id'));

        $submission->fresh(['files']);

        return new BaseResource($submission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Assignment $assignment
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment, $id)
    {
        DB::transaction(function () use ($assignment, $id) {
            /** @var AssignmentSubmission $submission */
            $submission = $assignment->submissions()->findOrFail($id);

            // Check if user has permission to delete
            $this->authorize('delete', $submission);

            // detach attached files
            $submission->files()->sync([]);

            $submission->delete();
        });


        return $this->respondNoContent();
    }
}
