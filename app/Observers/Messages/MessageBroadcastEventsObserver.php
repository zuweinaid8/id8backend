<?php

/**
 * Source referenced from stackoverflow answer here:
 * https://stackoverflow.com/a/46461789/5128251
 *
 */

namespace App\Observers\Messages;

use App\Events\Messages\MessageModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageBroadcastEventsObserver
 * @package App\Observers\Messages
 */
class MessageBroadcastEventsObserver
{
    public function created(Model $model)
    {
        broadcast(new MessageModelEvent($model, 'created'))->toOthers();
    }

    public function updated(Model $model)
    {
        broadcast(new MessageModelEvent($model, 'updated'))->toOthers();
    }

    public function deleted(Model $model)
    {
        broadcast(new MessageModelEvent($model, 'deleted'))->toOthers();
    }
}
