<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Team extends Pivot
{
    // It has an Id field
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rating', 'role'
    ];

    /**
     * Get the user that owns the team.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the league that owns the comment.
     */
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    /**
     * Get the results for the team.
     */
    public function results()
    {
        return $this->hasMany('App\Result');
    }
}
