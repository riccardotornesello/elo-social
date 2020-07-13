<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'points', 'rating_before', 'rating_after',
    ];

    /**
     * Get the match that owns the result.
     */
    public function match()
    {
        return $this->belongsTo('App\Match');
    }

    /**
     * Get the team that owns the result.
     */
    public function user()
    {
        return $this->belongsTo('App\Team');
    }
}
