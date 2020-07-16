<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SanctumController extends Controller
{
    public function issue(Request $request)
    {
        // Create the validator object for the input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        // If there are errors in the input returns an error
        if ($validator->fails()) return response()->json(['error' => $validator->errors()], 422);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) return response()->json(['error' => 'The provided credentials are incorrect.'], 401);

        return $user->createToken($request->device_name)->plainTextToken;
    }
}
