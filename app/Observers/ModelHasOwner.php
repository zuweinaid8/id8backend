<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class ModelHasOwner
{
    public function creating(Model $model)
    {
        $column = 'owner_id';

        if (is_null($model->$column)) {
            $model->$column = auth()->id();
        }
    }
}
