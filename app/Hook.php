<?php

namespace App;

use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Hook extends Model
{
    protected $guarded = [];

    protected $appends = [
        'last_called_at',
        'times_called',
        'url',
        'call_url',
        'email'
    ];


    /**
     * @param $slug
     *
     * @return mixed
     */
    public static function findBySlug( $slug )
    {
        return static::whereSlug($slug)->first();
    }


    /**
     * @param array $overrides
     *
     * @return mixed
     */
    public static function defaults( $overrides = [] )
    {
        return tap( new static([
            'name' => 'New Webhook',
            'description' => 'Created ' . Carbon::now()->format('Y-m-d H:i:s'),
            'ttl' => 60
        ] + $overrides ), function($hook) {
            $hook->save();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    public static function boot()
    {
        parent::boot();

        static::creating( function ( $hook ) {
            $salt = (int) mt_rand( 10000, 99999 ) . time();

            $hook->slug = Hashids::encode( $salt );
        } );
    }


    /**
     * Get the URL attribute
     * @return string
     */
    public function getUrlAttribute()
    {
        return "webhooks/{$this->slug}";
    }


    /**
     * Get the URL to call the webhook
     * @return string
     */
    public function getCallUrlAttribute()
    {
        return "webhooks/{$this->slug}/_call";
    }


    /**
     * Get the email address for the webhook
     * @return string
     */
    public function getEmailAttribute()
    {
        $mail_domain = config('mail.webhooks.domain');

        return "{$this->slug}@{$mail_domain}";
    }


    public function getLastCalledAtAttribute()
    {
        if ( ! $this->times_called ) return null;

        return $this->calls()->latest()->first()->created_at->format('Y-m-d H:i:s');
    }


    public function getTimesCalledAttribute()
    {
        return $this->calls()->count();
    }


    /**
     * Get the calls for the webhook
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }


    /**
     * Call this webhook
     * @param null $payload
     *
     * @return null|Call
     */
    public function call( $payload = null )
    {
        $call = new Call([
            'hook_id' => $this->id,
            'payload' => $payload
        ]);

        $call->save();

        return $call->fresh();
    }
}
