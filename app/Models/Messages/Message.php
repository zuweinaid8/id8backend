<?php

namespace App\Models\Messages;

use App\Models\BaseModel as Model;
use App\Models\Files\File;
use App\Observers\Messages\MessageBroadcastEventsObserver;
use App\Observers\Messages\MessageSenderObserver;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'body',
        'attachment_file_id',
    ];

    protected $touches = [
        'thread',
    ];

    public static function boot()
    {
        parent::boot();
        // Bind to Sender Observer
        static::observe(MessageSenderObserver::class);
        static::observe(MessageBroadcastEventsObserver::class);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['is_edited'] = $this->is_edited;
        $data['i_am_sender'] = $this->i_am_sender;

        return $data;
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function attachment()
    {
        return $this->belongsTo(File::class, 'attachment_file_id', 'id');
    }

    public function getIsEditedAttribute()
    {
        $created_at = Carbon::createFromTimeString($this->attributes['created_at']);
        $updated_at = Carbon::createFromTimeString($this->attributes['updated_at']);

        return $updated_at->greaterThan($created_at);
    }

    public function getIAmSenderAttribute()
    {
        // if not logged in
        if (is_null(request()->user())) {
            return false;
        }

        return $this->attributes['sender_id'] === request()->user()->id;
    }
}
