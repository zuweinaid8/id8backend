<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Models\Hurdle;
use App\Models\Mentor;
use App\Models\MentorshipArea;
use App\Models\Solution;
use App\Models\SolutionStage;
use App\Models\SolutionStatus;
use App\Models\Startup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SolutionService
{
    protected $model;

    public function __construct(Solution $solution)
    {
        $this->model = $solution;
    }

    /**
     * @param $data
     * @return mixed
     * @throws BaseException
     */
    public function save($data)
    {
        if (request()->user()->startup()->exists()) {
            /*  $status = request()->user()->startup()->whereHas('solution', function ($query) {
                  $query->whereHas('hurdle', function ($query) {
                      $query->Active(Carbon::now());
                  });
              })->get();
              if ($status->count() == 0) {*/
            $startup = request()->user()->startup()->first();
            $data['hurdle_id'] = $this->getActiveHurdle();
            $data['status_id'] = $this->getSubmittedStatus();
            $data['stage_id'] = $this->getSubmittedStage();
            return $startup->solution()->create($data);
            /*  } else {
                  throw new BaseException('solution already exists for the current active hurdle');
              }*/
        } else {
            // user can be admin or startup_user with no startup company
            throw new BaseException('LoggedIn User has no associated startup');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model
            ->view()
            ->findOrFail($id)
            ->delete();
    }

    /**
     * @return mixed
     * @throws BaseException
     */
    private function getActiveHurdle()
    {
        try {
            $hurdle = Hurdle::query()->Active(Carbon::now())->firstorFail();
        } catch (ModelNotFoundException $exception) {
            throw new BaseException('No active hurdle at the moment');
        }
        return $hurdle->id;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $solution = $this->model->view()->findOrFail($id);
        $solution->update($data);
        return $solution->fresh();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->model
            ->view()
            ->with([
                'startup',
                'stage',
                'mentorships',
                'status',
                'pitch_deck_file',
                'cover_photo_file',
                'business_model_file',
                'video_file',
            ])
            ->findOrFail($id);
    }

    /**
     * @return Solution[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->model::query()
            ->with([
                'startup',
                'stage',
                'mentorships',
                'status',
                'pitch_deck_file',
                'business_model_file',
                'video_file',
                'cover_photo_file',
            ])
            ->latest()
            ->view()
            ->paginate();
    }

    public function solutionsForMentor($mentor_id)
    {
        return $this->model
            ->whereHas('mentors', function ($query) use ($mentor_id) {
                $query->where('users.id', $mentor_id);
            })
            ->with([
                'startup',
                'stage',
                'mentorships',
                'status',
                'cover_photo_file',
                'pitch_deck_file',
                'business_model_file',
            ])
            ->latest()
            ->get();
    }

    public function solutionsForStartup($startup_id)
    {
        return $this->model->whereHas('startup', function ($query) use ($startup_id) {
            $query->where('startups.id', $startup_id);
        })->with([
            'startup',
            'stage',
            'mentorships',
            'status',
            'cover_photo_file',
            'pitch_deck_file',
            'business_model_file',
        ])->firstOrFail();
    }

    public function getActiveSolution()
    {
        $startup_id = Startup::query()->where('owner_id', request()->user()->id)->firstOrFail()->id;
        return $this->model->whereHas('startup', function ($query) use ($startup_id) {
            $query->where('startups.id', $startup_id);
        })->with([
            'startup',
            'stage',
            'mentorships',
            'status',
            'cover_photo_file',
            'pitch_deck_file',
            'business_model_file',
        ])->firstorFail();
    }

    /**
     * @return int
     */
    public function getSubmittedStatus()
    {
        return SolutionStatus::query()
            ->where('name', 'Under Review')
            ->first()
            ->id;
    }

    public function getSubmittedStage()
    {
        return SolutionStage::query()->where('name', 'Idea')->get(['id'])->pluck('id')->toArray()[0];
    }

    /**
     * @param $data
     * @param $mentor_id
     * @param string $action
     * @return mixed
     * @throws BaseException
     */
    public function assignMentor($data, $mentor_id, $action = 'save')
    {
        $mentor = $this->getMentor($mentor_id);
        $this->checkIfMentorIsAssignedArea($mentor_id, $data['mentor_ship_area_id']);
        $this->checkIfMentorIsAssignedSolution($mentor_id, $data['solution_id'], $data['mentor_ship_area_id'], $action);
        if ($action == 'delete') {
            $this->associateSolutionWithMentorAreas($data['solution_id'], $data['mentor_ship_area_id'], $action);
            return $mentor->solutions()->detach($data['solution_id'], ['mentor_ship_area_id' => $data['mentor_ship_area_id']]);
        } else {
            $this->associateSolutionWithMentorAreas($data['solution_id'], $data['mentor_ship_area_id'], $action);
            $mentor->solutions()->attach($data['solution_id'], ['mentor_ship_area_id' => $data['mentor_ship_area_id']]);
            $mentorship = $this->getMentorship($mentor_id, $data);
            return $mentorship;
        }
    }

    private function getMentorship($mentor_id, $data)
    {
        $solution = $this->model->where("id", $data['solution_id'])->whereHas('mentorships', function ($query) use ($data, $mentor_id) {
            $query->where('mentor_id', $mentor_id)
                ->where('mentor_ship_area_id', $data['mentor_ship_area_id']);
        })->with(['mentorships'])->get();
        $mentorship = collect([]);
        $mentorships = collect($solution->toArray()[0]['mentorships']);
        $mentorships->each(function ($item, $key) use ($mentor_id, $data, $mentorship) {
            if ($item['mentor']['id'] == $mentor_id && $item['mentorship_area']['id'] == $data['mentor_ship_area_id']) {
                $mentorship->push($item);
            } else {
                // nothing matches
            }
        });
        return $mentorship;
    }

    private function getMentor($mentor_id)
    {
        return Mentor::query()->where('type', 'mentor')->findOrFail($mentor_id);
    }

    /**
     * @param $mentor_id
     * @param $solution_id
     * @param $mentor_area_id
     * @throws BaseException
     */
    private function checkIfMentorIsAssignedSolution($mentor_id, $solution_id, $mentor_area_id, $action)
    {
        $check = DB::table('solution_mentors')
            ->where('mentor_ship_area_id', $mentor_area_id)
            ->where('mentor_id', $mentor_id)
            ->where('solution_id', $solution_id)->exists();
        if ($check) {
            if ($action == 'save') {
                throw new BaseException('solution is already assigned with this mentor for this mentorship_area');
            }
            return;
        }
        return;
    }

    /**
     * @param $mentor_id
     * @param $mentor_area_id
     * @throws BaseException
     */
    private function checkIfMentorIsAssignedArea($mentor_id, $mentor_area_id)
    {
        $check = DB::table('mentor_with_areas')
            ->where('mentor_id', $mentor_id)
            ->where('area_id', $mentor_area_id)->exists();
        if (!$check) {
            throw new BaseException('this mentor is not assigned with this mentorship_area');
        }
        return;
    }

    private function associateSolutionWithMentorAreas($solution_id, $mentor_area_id, $action)
    {
        $solution = $this->model->findOrfail($solution_id);
        $mentor_area = MentorshipArea::query()->findOrFail($mentor_area_id);
        if ($action == 'save') {
            $solution->mentor_areas()->attach($mentor_area);
        } else {
            $solution->mentor_areas()->detach($mentor_area);
        }
    }
}
