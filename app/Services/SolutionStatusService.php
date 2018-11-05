<?php

namespace App\Services;

use App\Models\Solution;
use App\Models\SolutionStage;
use App\Models\SolutionStatus;

class SolutionStatusService
{
    protected $model;

    public function __construct(SolutionStatus $model)
    {
        $this->model = $model;
    }

    public function getAllSolution_Status()
    {
        return $this->model::query()->paginate();
    }
}
