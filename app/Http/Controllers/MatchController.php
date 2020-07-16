<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

use App\League;
use App\Match;
use App\Result;

class MatchController extends Controller //TODO: pulire codice
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

    private function rate($ratingA, $ratingB, $pointsA, $pointsB)
    {
        $expectedA = 1 / (1 + pow(10, ($ratingB - $ratingA) / 400));
        $expectedB = 1 - $expectedA;

        $k = 50;

        $difference = abs($pointsA - $pointsB);

        $g = 1;
        if ($difference == 2) $g = 3 / 2;
        if ($difference > 2) $g = (11 + $difference) / 8;

        if ($pointsA > $pointsB) $resultA = 1;
        if ($pointsA = $pointsB) $resultA = 0.5;
        if ($pointsA < $pointsB) $resultA = 0;

        $resultB = 1 - $resultA;

        $newRatingA = $ratingA + $k * $g($resultA - $expectedA);
        $newRatingB = $ratingB + $k * $g($resultB - $expectedB);

        return [$newRatingA, $newRatingB];
    }

    public function create(Request $request, League $league)
    {
        // Create the validator object for the input
        $validator = Validator::make($request->all(), [
            'results' => 'required|array',
            'results.*.team_id' => 'required|numeric',
            'results.*.points' => 'required|numeric',
        ]);

        // If there are errors in the input returns an error
        if ($validator->fails()) return response()->json(['error' => $validator->errors()], 422);

        // Check for admin permission
        $permission = Gate::inspect('administrate', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        // Parse input
        $teamAId = $request['results'][0]['team_id'];
        $teamBId = $request['results'][1]['team_id'];
        $teamAPoints = $request['results'][0]['points'];
        $teamBPoints = $request['results'][1]['points'];

        // Find the teams
        $teamA = $league->teams()->where('id', $teamAId);
        $teamB = $league->teams()->where('id', $teamBId);

        if ($teamA === null || $teamB === null) return response()->json(['error' => 'User not found'], 409);

        // Create the match and the results
        $match = new Match;
        $match->save();

        [$newRatingA, $newRatingB] = $this->rate($teamA->rating, $teamB->rating, $teamAPoints, $teamBPoints);

        $resultA = Result::create([
            'points' => $teamAPoints,
            'rating_before' => $teamA->rating,
            'rating_after' => $newRatingA
        ]);
        $resultA->team = $teamA;

        $resultB = Result::create([
            'points' => $teamBPoints,
            'rating_before' => $teamB->rating,
            'rating_after' => $newRatingB
        ]);
        $resultB->team = $teamB;

        $match->results()->saveMany([$resultA, $resultB]);

        // Update the ratings
        $teamA->rating = $newRatingA;
        $teamA->save();

        $teamB->rating = $newRatingB;
        $teamB->save();

        // Return success
        return response()->json(null, 201);
    }
}
