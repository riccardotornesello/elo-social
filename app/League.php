<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'base_points',
    ];

    /**
     * Get the teams in the league.
     */
    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    /**
     * Get the game that owns the league.
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * Get all the league invites.
     */
    public function invites()
    {
        return $this->hasMany('App\Invite');
    }

    /**
     * Get all the matches in the league.
     */
    public function matches()
    {
        return $this->hasMany('App\Match');
    }

    /**
     * Get all of the users for the league.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'teams')->using('App\Team')->withPivot('name', 'rating', 'role')->withTimestamps();
    }
}
