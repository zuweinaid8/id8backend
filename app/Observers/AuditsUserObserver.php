<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class AuditUserObserver
{
    /**
     * Listen to the created event.
     *
     * @param Model $model
     *
     * @return void
     */
    public function creating(Model $model)
    {
        logger('Creating event on AuditUserObserver triggered');

        // Check if user is logged in
        if (! auth()->check()) {
            logger('No logged in user found. Skipping AuditUserObserver on creating '. get_class($model));
            return;
        }

        // bind user audit logging
        $model->created_by_id = auth()->id() ?? $model->created_by_id;
        $model->updated_by_id = auth()->id() ?? $model->created_by_id;
    }

    /**
     * Listen to the updating event.
     *
     * @param Model $model
     *
     * @return void
     */
    public function updating(Model $model)
    {
        // Check if user is logged in
        if (! auth()->check()) {
            logger('No logged in user found. Skipping AuditUserObserver on updating '. get_class($model) . ' id:' . $model->id);
            return;
        }

        $model->updated_by_id = auth()->id();
    }
}
