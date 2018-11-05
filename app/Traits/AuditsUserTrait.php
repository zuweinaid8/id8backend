<?php

namespace App\Traits;

use App\User;
use App\Observers\AuditUserObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait AuditsUserTrait
 * @package App\Utils
 * @mixin Model
 */
trait AuditsUserTrait
{
    public static function bootAuditsUserTrait()
    {
        static::observe(AuditUserObserver::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }
}
