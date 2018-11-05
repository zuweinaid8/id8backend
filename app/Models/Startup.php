<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Models\Files\File;
use App\User;

class Startup extends Model
{
    protected $table = 'startups';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'year_founded',
        'description',
        'business_licence_no',
        'tin_no',
        'web_address',
        'physical_address',
        'cover_photo_file_id',
        'logo_file_id',
    ];

    protected $casts = [
        'team' => 'object',
        'is_registered' => 'boolean',
        'physical_address' => 'object',
    ];

    protected $with = [
        'owner'
    ];

    /**
     * Return eloquent relation to owner company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function solution()
    {
        return $this->hasOne(Solution::class, 'startup_id', 'id');
    }

    public function cover_photo_file()
    {
        return $this->belongsTo(File::class, 'cover_photo_file_id', 'id')
            ->withDefault([
                'mime_type' => 'image/jpeg',
                'url' => 'https://id8-assets.s3-eu-west-1.amazonaws.com/images/company-cover-photo.jpg',
            ]);
    }

    public function logo_file()
    {
        return $this->belongsTo(File::class, 'logo_file_id', 'id')
            ->withDefault([
                'mime_type' => 'image/jpeg',
                'url' => 'https://id8-assets.s3-eu-west-1.amazonaws.com/images/company-logo.png',
            ]);
    }

    public function scopeOwner($query)
    {
        return $query->where('owner_id', request()->user()->id);
    }

    public function configureViewQuery($query)
    {
        return $query->where('owner_id', request()->user()->id);
    }

    public function scopePeriod($query, $period)
    {
        return $query->whereBetween('created_at', $period['start'], $period['end']);
    }
}
