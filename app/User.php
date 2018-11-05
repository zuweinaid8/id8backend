<?php

namespace App;

use App\Filters\Users\UserFilter;
use App\Models\Files\File;
use App\Models\Messages\Thread;
use App\Traits\HasTimezone;
use EloquentFilter\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Lunaweb\EmailVerification\Traits\CanVerifyEmail;

class User extends Authenticatable
{
    protected $table = 'users';

    use Notifiable, HasApiTokens, HasTimezone, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'meta',
        'profile_picture_file_id',
        'cover_photo_file_id',
        'name_of_fintech',
        'year_founded',
        'description',
        'idea',
    ];

    protected $attributes = [
        'meta' => '{}',
    ];

    /**
     * Set format to include timezone
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $casts = [
        'meta' => 'object',
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function modelFilter()
    {
       return $this->provideFilter(UserFilter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function startup()
    {
        return $this->hasOne('\App\Models\Startup', 'owner_id', 'id');
    }

    public function profile_photo_file()
    {
        return $this->hasOne('\App\Models\Files\File', 'id', 'profile_picture_file_id')
            ->withDefault([
                'mime_type' => 'image/jpeg',
                'url' => 'https://id8-assets.s3-eu-west-1.amazonaws.com/images/user-avatar.png',
            ]);
    }

    public function cover_photo_file()
    {
        return $this->belongsTo(File::class, 'cover_photo_file_id', 'id')
            ->withDefault([
                'mime_type' => 'image/jpeg',
                'url' => 'https://id8-assets.s3-eu-west-1.amazonaws.com/images/user-profile-cover-photo.jpg',
            ]);
    }

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'thread_participants')
            ->withPivot([
                'last_read',
            ]);
    }

    public function scopeOfStartup($query)
    {
        return $query->where('type', 'startup');
    }

    public function isAdmin()
    {
        return $this->type == 'admin';
    }

    public function isMentor()
    {
        return $this->type == 'mentor';
    }

    public function isStartup()
    {
        return $this->type == 'startup';
    }

    /**
     * Name Accessors
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
