<?php

namespace App\Services;

use App\Models\Solution;
use App\Models\SolutionStage;

class SolutionStageService
{
    protected $model;

    public function __construct(SolutionStage $model)
    {
        $this->model = $model;
    }

    public function getAllSolution_Stages()
    {
        return $this->model::query()->get();
    }
}
