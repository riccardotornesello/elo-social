<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

use App\League;
use App\Http\Resources\LeagueResource;
use App\Http\Resources\TeamResource;

class LeagueController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function create(Request $request)
    {
        // Create the validator object for the input
        $validator = Validator::make($request->all(), [
            'league_name' => 'required|unique:leagues,name',
            'base_points' => 'required|numeric|between:1000,10000',
            'team_name' => 'required',
        ]);

        // If there are errors in the input returns an error
        if ($validator->fails()) return response()->json(['error' => $validator->errors()], 422);

        // Create the league and the first team
        $league = $request->user()->leagues()->create([
            'name' => $request['league_name'],
            'base_points' => $request['base_points'],
        ], [
            'name' => $request['team_name'],
            'rating' => $request['base_points'],
            'role' => 'admin',
        ]);

        // Return the league json
        return (new LeagueResource($league))->response()->setStatusCode(201)->header('Location', '/leagues/' . $league->id);
    }

    public function list(Request $request)
    {
        $user = $request->user();
        $leagues = $user->leagues()->get();
        return LeagueResource::collection($leagues)->response()->setStatusCode(200);
    }

    public function view(Request $request, League $league)
    {
        if ($league === null) return response()->json(['error' => 'League not found'], 404);

        $permission = Gate::inspect('view', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        return (new LeagueResource($league))->response()->setStatusCode(200);
    }

    public function teams(Request $request, League $league)
    {
        if ($league === null) return response()->json(['error' => 'League not found'], 404);

        $permission = Gate::inspect('view', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        return TeamResource::collection($league->teams()->get())->response()->setStatusCode(200);
    }

    public function join(Request $request, League $league)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required'
        ]);

        // Check input
        if ($validator->fails()) return response()->json(['error' => $validator->errors()], 422);

        // Check for permission
        $permission = Gate::inspect('join', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        // Add the player
        $user = $request->user;

        $league->users()->save($user, [
            'name' => $request['team_name'],
            'rating' => $league->base_points,
            'role' => 'player',
        ]);

        $invite = $league->invites()->where('email', $user->email)->first();
        $invite->delete();
    }
}
