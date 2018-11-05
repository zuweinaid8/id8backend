<?php

namespace App\Models\Messages;

use App\Models\BaseModel as Model;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
    ];

    public static function boot()
    {
        // Bind to current user scope
        static::addGlobalScope(function ($query) {
            return $query->whereHas('participants', function ($q) {
                return $q->where('users.id', request()->user()->id);
            });
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'thread_id', 'id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'thread_participants')
            //->withTimestamps()
            ->withPivot([
                'last_read',
            ]);
    }

    public function last_message()
    {
        return $this->hasOne(Message::class, 'thread_id', 'id')
            ->orderBy('id', 'desc');
    }

    /**
     * Get count of unread messages in the current thread by the user.
     *
     * @param $user_id
     * @return int
     */
    public function countUnread($user_id)
    {
        try {
            $participants = $this->participants()
                ->findOrFail($user_id);
            $last_read = $participants->pivot->last_read;

            if (is_null($last_read)) {
                return $this->messages()
                    ->where('sender_id', '<>', $user_id)
                    ->count();
            } else {
                return $this->messages()
                    ->where('sender_id', '<>', $user_id)
                    ->where('created_at', '>', $last_read)
                    ->count();
            }
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }

        return 0;
    }

    public function markAsRead($user)
    {
        // update last read time for user on this thread
        $this->participants()->updateExistingPivot(
            $user,
            ['last_read' => now()],
            false
        );
    }
}
