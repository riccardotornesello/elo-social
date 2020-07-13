<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

use App\League;
use App\Http\Resources\InviteResource;

class InviteController extends Controller
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

    public function create(Request $request, League $league)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        // Check input
        if ($validator->fails()) return response()->json(['error' => $validator->errors()], 422);

        // Check for admin permission
        $permission = Gate::inspect('administrate', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        // If user already invited in that league return an error
        if ($league->invites()->where('email', $request['email'])->exists())
            return response()->json(['error' => 'User already invited'], 409);

        // Create the invite
        $invite = $league->invites()->create([
            'email' => $request['email'],
        ]);

        // Return success
        return response()->json(null, 201);
    }

    public function list(Request $request, League $league)
    {
        $permission = Gate::inspect('administrate', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 403);
        }

        return InviteResource::collection($league->invites())->response()->setStatusCode(200);
    }
}
