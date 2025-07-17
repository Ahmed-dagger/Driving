<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CourseRequest;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display a listing of sessions.
     */
    public function index()
    {
        $sessions = Session::with(['courseRequest', 'instructor'])->get();

        return response()->json([
            'data' => $sessions,
        ]);
    }

    /**
     * Store a newly created session.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_request_id' => 'required|exists:course_requests,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,accepted,rejected,completed,canceled',
            'rejection_reason' => 'nullable|string|max:500',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'rate' => 'nullable|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = Session::create($validator->validated());

        return response()->json([
            'message' => 'Session created successfully.',
            'data' => $session,
        ], 201);
    }

    /**
     * Display a single session.
     */
    public function show(Session $session)
    {
        $session->load(['courseRequest', 'instructor']);

        return response()->json([
            'data' => $session,
        ]);
    }

    /**
     * Update a session.
     */
    public function update(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), [
            'course_request_id' => 'required|exists:course_requests,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,accepted,rejected,completed,canceled',
            'rejection_reason' => 'nullable|string|max:500',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'rate' => 'nullable|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session->update($validator->validated());

        return response()->json([
            'message' => 'Session updated successfully.',
            'data' => $session,
        ]);
    }

    /**
     * Delete a session.
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return response()->json([
            'message' => 'Session deleted successfully.',
        ]);
    }

    public function InstructorSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|number|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $requests = CourseRequest::where('instructor_id', $validator->validated()['instructor_id'])
            ->with(['sessions' => function ($query) {
                $query->with('instructor');
            }])
            ->get();

        return response()->json([
            'data' => $requests,
            'message' => 'Instructor Requests retrieved successfully.',
        ] , 200);
    }
}
