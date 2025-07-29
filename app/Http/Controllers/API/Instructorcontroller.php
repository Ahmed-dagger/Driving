<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = User::where('user_type', 'instructor')->get();

        return response()->json($instructors);
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

            $user = User::firstOrCreate(
                ['firebase_uid' => $firebaseUid],
                [
                    'firebase_uid' => $firebaseUid,
                    'name' => $validator->validated()['name'],
                    'phone' => $validator->validated()['phone'],
                    'password' => $validator->validated()['password'],
                    'user_type' => 'instructor',
                    'phone_verified_at' => $phoneVerifiedAt,
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


    public function documentUpload(Request $request, User $user)
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



    public function instructorLogin(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Retrieve the user
        $user = User::where('phone', $validated['phone'])->first();

        // Check if user exists and is an instructor
        if (!$user || !$user->isInstructor()) {
            return response()->json([
                'error' => 'User not found or not an instructor.',
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

    public function rate(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Update the 'rate' column for the user
        $user->rate = $validated['rating'];
        $user->save();

        return response()->json([
            'message' => 'Rating submitted successfully.',
            'user' => $user,
            'rating' => $validated['rating'],
        ]);
    }

    public function instructorProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::findOrFail($validator->validated()['instructor_id']);

        if (!$user->isInstructor()) {
            return response()->json(['message' => 'Unauthorized. Not an instructor.'], 403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_type' => $user->user_type,
            'status' => $user->status,
            'rate' => $user->rate,
            'car_image_url' => $user->getFirstMediaUrl('car_images'),
            'license_image_url' => $user->getFirstMediaUrl('license_images'),
            'profile_image_url' => $user->getFirstMediaUrl('profile_images'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'profile_image' => 'sometimes|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'car_image' => 'sometimes|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'license_image' => 'sometimes|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // if(!$user->isInstructor()) {
        //     return response()->json(['message' => 'Unauthorized. Not an instructor.'], 403);
        // }

        $data = $validator->validated();

        // Handle password hashing if provided
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // Update user details
        $user->update($data);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $user
                ->addMedia($request->file('profile_image'))
                ->toMediaCollection('profile_images');
        }

        // Handle car image upload
        if ($request->hasFile('car_image')) {
            $user
                ->addMedia($request->file('car_image'))
                ->toMediaCollection('car_images');
        }

        // Handle license image upload
        if ($request->hasFile('license_image')) {
            $user
                ->addMedia($request->file('license_image'))
                ->toMediaCollection('license_images');
        }

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
            'profile_image_url' => $user->getFirstMediaUrl('profile_images'),
            'car_image_url' => $user->getFirstMediaUrl('car_images'),
            'license_image_url' => $user->getFirstMediaUrl('license_images'),
        ]);
    }
}
