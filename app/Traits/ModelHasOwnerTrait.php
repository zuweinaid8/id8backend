<?php

namespace App\Traits;

use App\User;
use App\Observers\ModelHasOwner;
use App\Scopes\OwnerScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait ModelHasOwnerTrait
 * @package App\Traits
 * @mixin Model
 */
trait ModelHasOwnerTrait
{
    public static function bootModelHasOwnerTrait()
    {
        // Bind to Has Owner Observer
        static::observe(ModelHasOwner::class);

        // Bind to company scope
        static::addGlobalScope(app(OwnerScope::class));
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
