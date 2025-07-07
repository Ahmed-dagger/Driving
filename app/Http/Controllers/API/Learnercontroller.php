<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwilioVerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth as FirebaseAuth;

class Learnercontroller extends Controller
{
    public function index()
    {
        $learners = User::where('user_type', 'learner')->get();

        return response()->json($learners);
    }


    public function firebaseLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idToken' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $auth = app('firebase.auth');

            $verifiedIdToken = $auth->verifyIdToken($request->idToken);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $phoneNumber = $verifiedIdToken->claims()->get('phone_number');
            $email = $verifiedIdToken->claims()->get('email');

            if (!$phoneNumber && !$email) {
                return response()->json([
                    'error' => 'Neither phone number nor email found in the ID token.'
                ], 400);
            }

            $user = User::firstOrCreate(
                ['firebase_uid' => $firebaseUid],
                [
                    'phone' => $phoneNumber,
                    'email' => $email ?? null,
                    'name' => 'Unknown',
                    'password' => bcrypt(Str::random(32)),
                    'user_type' => 'learner',
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            return response()->json(['error' => 'Invalid ID token.'], 401);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }
}
