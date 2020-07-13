<?php

namespace App\Policies;

use App\League;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LeaguePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function administrate(User $user, League $league)
    {
        if (!$league->users->contains($user)) return Response::deny('User not in the league');
        if ($league->users->where('id', $user->id)->first()->pivot->role !== 'admin') return Response::deny('User is not an administrator');
        return Response::allow();
    }

    public function join(User $user, League $league)
    {
        if ($league->users->contains($user)) return Response::deny('User alredy in the league');
        if ($league->invites()->where('email',$user->email)->first() === null) return Response::deny('User not invited');
        return Response::allow();
    }

    public function view(User $user, League $league)
    {
        return $league->users->contains($user)
            ? Response::allow()
            : Response::deny('User not in the league');
    }
}
