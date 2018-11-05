<?php

namespace App\Models;

class SolutionStage extends BaseModel
{
    protected $table = 'solution_stages';

    protected $fillable = [
        'code',
        'sequence',
        'name',
        'description',
    ];
}
