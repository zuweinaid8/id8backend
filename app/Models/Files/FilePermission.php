<?php

namespace App\Models\Files;

use App\Models\BaseModel as Model;

class FilePermission extends Model
{
    protected $fillable = [
        'type',
        'access',
        'user_id',
        'role_id',
    ];

    protected $casts = [
        'file_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
