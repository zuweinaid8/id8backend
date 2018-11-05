<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Requests\Assignments\AssignmentRequest;
use App\Http\Resources\Assignments\AssignmentResource;
use App\Models\Assignments\Assignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Assignment resource representation.
 *
 * @Resource("Assignments", uri="/assignments")
 */
class AssignmentController extends Controller
{
    /**
     * List all assignments
     *
     * @Get("/")
     * @return \App\Http\Resources\BaseCollection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $data = Assignment::query()
            ->with(['settings', 'mentorship'])
            ->paginate();

        return AssignmentResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AssignmentRequest $request
     * @return mixed
     */
    public function store(AssignmentRequest $request)
    {
        $assignment = DB::transaction(function () use ($request) {
            // Create assignment
            $assignment = Assignment::create($request->all());

            $settings = $this->getDefaultSettings();
            $settings = array_merge($settings, $request->input('settings', []));

            // Store settings
            $assignment->settings()
                ->create($settings);

            return $assignment->load(['settings', 'mentorship']);
        });

        return new AssignmentResource($assignment);
    }

    /**
     * Display the specified resource.
     *
     * @param Assignment $assignment
     * @return AssignmentResource
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['settings', 'mentorship']);

        return new AssignmentResource($assignment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AssignmentRequest $request
     * @param Assignment $assignment
     * @return AssignmentResource
     */
    public function update(AssignmentRequest $request, Assignment $assignment)
    {
        DB::transaction(function () use ($request, $assignment) {
            $assignment->update($request->all());
            $assignment->save();

            $assignment->settings()->update($request->input('settings'));

            $assignment->load(['settings', 'mentorship']);
        });

        return new AssignmentResource($assignment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Assignment $assignment
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Assignment $assignment)
    {
        // Check if user authorized to delete model
        $this->authorize(
            'delete',
            $assignment,
            'Unauthorized action! Only mentor who assigned this assignment can delete it'
        );

        DB::transaction(function () use ($assignment) {
            // TODO clean up related models
            $assignment->delete();
        });

        return $this->respondNoContent();
    }

    protected function getDefaultSettings()
    {
        return [
            'start_date_enabled' => true,
            'start_date' => now(),
            'due_date_enabled' => true,
            'due_date' => now()->addWeek(),
            'cut_off_date_enabled' => false,
            'cut_off_date' => null,
            'grading_due_date_enabled' => now()->addWeeks(2),
            'online_text_enabled' => true,
            'file_submission_enabled' => true,
            'notifications_enabled' => true,
            'online_text_word_limit' => 1000,
            'max_uploaded_files' => 1,
            'max_submission_size' => 128000,
        ];
    }
}
