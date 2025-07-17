<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = User::where('user_type', 'instructor')->get();

        return response()->json($instructors);
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
                    'user_type' => 'instructor',
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

    public function documentUpload(Request $request , User $user)
    {
        $validator = Validator::make($request->all(), [
            'car_image' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'license_image' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'profile_image' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        

    
        // Attach files to their collections
        if ($request->hasFile('car_image')) {
            $user
                ->addMedia($request->file('car_image'))
                ->toMediaCollection('car_images');
        }

        if ($request->hasFile('license_image')) {
            $user
                ->addMedia($request->file('license_image'))
                ->toMediaCollection('license_images');
        }

        if ($request->hasFile('profile_image')) {
            $user
                ->addMedia($request->file('profile_image'))
                ->toMediaCollection('profile_images');
        }

        return response()->json([
            'message' => 'Documents uploaded successfully.',
            'car_image_url' => $user->getFirstMediaUrl('car_images'),
            'license_image_url' => $user->getFirstMediaUrl('license_images'),
            'profile_image_url' => $user->getFirstMediaUrl('profile_images'),
        ]);
    }
}
