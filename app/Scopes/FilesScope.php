<?php

namespace App\Scopes;

use App\Models\Files\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilesScope implements Scope
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
        /** @var File $model */
        $table = $model->getTable();

        $owner_column = 'owner_id';

        if (!auth()->check()) {
            logger('No user logged in. Skipping application of File Scope');
            return;
        }

        if(auth()->user()->isAdmin()) {
            logger('Skipping application of File Scope for Admin');
            return;
        }

        $builder
            ->whereHas('permissions', function ($q) {
                return $q
                    ->where(function ($q) {
                        // Check user permission
                        return $q->where('type', '=', 'user')
                            ->where('user_id', '=', auth()->id());
                    })
                    ->orWhere(function ($q) {
                        // Check role permission
                        return $q->where('type', '=', 'role')
                            ->where('role_id', '=', auth()->user()->type);
                    });
            })
            // Check owner permission
            ->orWhere($table . '.' . $owner_column, '=', auth()->id());
    }
}
