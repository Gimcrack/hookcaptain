<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array'
    ];

    /**
     * A Call belongs to one Hook
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hook()
    {
        return $this->belongsTo(Hook::class);
    }


    /**
     * Get all the calls for a hook
     * @param Builder $query
     * @param Hook    $hook
     *
     * @return mixed
     */
    public function scopeOfHook( Builder $query, Hook $hook )
    {
        return $query->whereHookId($hook->id);
    }
}
