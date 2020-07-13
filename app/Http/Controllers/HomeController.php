<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;

use App\League;
use App\Invite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function league(Request $request, League $league)
    {
        $permission = Gate::inspect('view', $league);
        if (!$permission->allowed()) {
            return response()->json(['error' => $permission->message()], 422);
        }

        return view('league');
    }

    public function join(Request $request, League $league)
    {
        return view('join');
    }

    public function list(Request $request)
    {
        $user = $request->user();

        $invites = Invite::where('email', $user->email)->league()->get();

        return $invites;
    }
}
