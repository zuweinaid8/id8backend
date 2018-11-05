<?php

namespace App\Models;

class SolutionStatus extends BaseModel
{
    protected $table = 'solution_statuses';
    protected $fillable = [
        'name',
        'description'
    ];

    public function configureViewQuery($query)
    {
        // TODO: Implement configureViewQuery() method.
    }
}
