<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwilioVerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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


    public function firebaseRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idToken' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'license_number' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $auth = app('firebase.auth');
            $verifiedIdToken = $auth->verifyIdToken($validator->validated()['idToken']);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $phoneVerifiedAt =  $validator->validated()['phone'] ? now() : null;

            $user = User::updateOrCreate(
                ['firebase_uid' => $firebaseUid],
                [
                    'name' => $validator->validated()['name'],
                    'phone' => $validator->validated()['phone'],
                    'password' => $validator->validated()['password'],
                    'user_type' => 'learner',
                    'phone_verified_at' => $phoneVerifiedAt,
                ]
            );


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'user registered successfully',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            return response()->json(['error' => 'Invalid ID token.'], 401);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }

    public function learnerLogin(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Retrieve the user
        $user = User::where('phone', $validated['phone'])->first();

        // Check if user exists and is a learner
        if (!$user || !$user->isLearner()) {
            return response()->json([
                'error' => 'User not found or not a learner.',
            ], 404);
        }

        // Verify password
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'error' => 'Invalid phone or password.',
            ], 401);
        }

        // Create a personal access token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Success response
        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function learnerProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learner_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::findOrFail($validator->validated()['learner_id']);

        if (!$user->isLearner()) {
            return response()->json(['message' => 'Unauthorized. Not a learner.'], 403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_type' => $user->user_type,
            'status' => $user->status,
        ]);
    }
}
