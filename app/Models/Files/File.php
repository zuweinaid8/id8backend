<?php

namespace App\Models\Files;

use App\Models\BaseModel as Model;
use App\Observers\ModelHasOwner;
use App\Scopes\FilesScope;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Class File
 * @package App\Models\Files
 * @property string name
 * @property string mime_type
 * @property int size
 * @property string extension
 * @property string notes
 * @property string disk
 * @property string path
 * @property boolean is_public
 * @property \stdClass meta
 */
class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'mime_type',
        'size',
        'extension',
        'notes',
        'disk',
        'path',
        'is_public',
        'meta',
    ];

    protected $hidden = [
        'path',
        'disk',
    ];

    protected $casts = [
        'meta' => 'object',
        'is_public' => 'boolean',
        'size' => 'double',
        'owner_id' => 'integer',
    ];

    protected $attributes = [
        'is_public' => false,
    ];

    public static function boot()
    {
        // Bind to Has Owner Observer
        static::observe(ModelHasOwner::class);
        // Bind to file scope for permissions
        static::addGlobalScope(app(FilesScope::class));

        parent::boot();
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['url'] = $this->url;
        $data['is_image'] = (bool) $this->is_image;
        $data['is_video'] = (bool) $this->is_video;
        return $data;
    }

    public function getUrlAttribute($value)
    {
        if(!is_null($value)) return $value;

        $tempUrlActiveMinutes = 30;

        if ($this->disk === 's3') {
            return Storage::disk($this->disk)
                ->temporaryUrl(
                    $this->path,
                    now()->addMinutes($tempUrlActiveMinutes)
                );
        } else {
            return URL::temporarySignedRoute(
                'open_file',
                now()->addMinutes($tempUrlActiveMinutes),
                ['file' => $this->id]
            );
        }
    }

    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, [
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/png',
        ]);
    }

    public function getIsVideoAttribute()
    {
        return in_array($this->mime_type, [
            'video/mp4',
        ]);
    }

    public function permissions()
    {
        return $this->hasOne(FilePermission::class);
    }

    /**
     * @param \App\User $user
     */
    public function addReader(User $user)
    {
        $this->permissions()
            ->firstOrCreate([
                'type' => 'user',
                'user_id' => $user->id,
            ], [
                'access' => 'reader',
            ]);
    }

    /**
     * @param array $users
     */
    public function addReaders($users)
    {
        DB::transaction(function () use ($users) {
            foreach ($users as $user) {
                $this->addReader($user);
            }
        });
    }
}
