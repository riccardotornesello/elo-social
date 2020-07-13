<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];

    /**
     * Get the league that owns the invite.
     */
    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
