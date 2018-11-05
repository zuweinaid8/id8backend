<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $user = request()->user();
        $owner_column = 'owner_id';

        if (is_null($user)) {
            logger('No user logged in. Skipping application of Owner Scope');
            return;
        }

        $builder->where($table . '.' . $owner_column, $user->id);
    }
}
