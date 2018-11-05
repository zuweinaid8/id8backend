<?php

namespace App\Observers\Messages;

use Illuminate\Database\Eloquent\Model;

class MessageSenderObserver
{
    public function creating(Model $model)
    {
        if (is_null($model->sender_id)) {
            $model->sender_id = request()->user()->id;
        }
    }
}
