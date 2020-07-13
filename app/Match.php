<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * Get the league that owns the match.
     */
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    /**
     * Get the results for the match.
     */
    public function results()
    {
        return $this->hasMany('App\Result');
    }
}
