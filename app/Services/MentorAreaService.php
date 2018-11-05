<?php

namespace App\Services;

use App\Commons\ResponseUtils;
use App\Models\Mentor;
use App\Models\MentorshipArea;

class MentorAreaService
{
    use ResponseUtils;
    protected $model;
    public function __construct(MentorshipArea $model)
    {
        $this->model = $model;
    }

    public function toggle($area_id, $mentor_id)
    {
        $mentor = $this->getMentor($mentor_id);
        return $mentor->mentor_areas()->toggle($area_id);
    }

    public function update($id, $data)
    {
        $mentor_ship_area = $this->model->findOrFail($id);
        $mentor_ship_area->update($data);
        return $mentor_ship_area->fresh();
    }

    public function delete($id)
    {
        $mentor_ship_area = $this->model->findOrFail($id);
        return $mentor_ship_area->delete();
    }

    public function getAllMentor_Areas()
    {
        return MentorshipArea::query()->get();
    }

    public function save($request)
    {
        return $this->model->create($request->all());
    }

    private function getMentor($mentor_id)
    {
        return Mentor::query()->where('type', 'mentor')->findOrFail($mentor_id);
    }

    public function show($id)
    {
        return $this->model
            ->findOrFail($id);
    }
}
