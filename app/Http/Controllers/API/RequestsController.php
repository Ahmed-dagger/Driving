<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\CourseRequest; // Ensure this is the correct model for requests
use App\Models\Session;
use Illuminate\Support\Facades\Log;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseRequests = CourseRequest::with(['learner', 'instructor', 'package'])->get();
        return response()->json(['data' => $courseRequests]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learner_id' => 'required|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'start_date' => 'required|date',
            'location_city' => 'required|string',
            'location_area' => 'required|string',
            'has_learner_car' => 'boolean',
            'requires_transport' => 'boolean',
            'total_price' => 'numeric|min:0',
            'type' => ['required', Rule::in(['general', 'private'])],
            'status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected'])],
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        $courseRequest = CourseRequest::create($validated);

        return response()->json(['data' => $courseRequest], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseRequest $courseRequest)
    {
        $courseRequest->load(['learner', 'instructor', 'package', 'sessions']);
        return response()->json(['data' => $courseRequest]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseRequest $courseRequest)
    {
        $validator = Validator::make($request->all(), [
            'learner_id' => 'required|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'requested_start_date' => 'nullable|date',
            'location_city' => 'required|string',
            'location_area' => 'required|string',
            'has_learner_car' => 'boolean',
            'requires_transport' => 'boolean',
            'total_price' => 'numeric|min:0',
            'type' => ['required', Rule::in(['general', 'private'])],
            'status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected'])],
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $courseRequest->update($validated);

        return response()->json(['data' => $courseRequest]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseRequest $courseRequest)
    {
        try {
            $deleted = $courseRequest->delete();

            if (! $deleted) {
                Log::error("Failed to delete CourseRequest ID: {$courseRequest->id}");
                return response()->json(['message' => 'Delete failed.'], 500);
            }

            return response()->json(['message' => 'Course request deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Delete error: " . $e->getMessage());
            return response()->json(['message' => 'Error deleting request.'], 500);
        }
    }


    /**
     * Claim a general course request by an instructor.
     */
    public function claim(Request $request, CourseRequest $courseRequest)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        if ($courseRequest->instructor_id) {
            return response()->json(['message' => 'Already claimed'], 409);
        }

        $courseRequest->update([
            'instructor_id' => $validator->validated()['instructor_id'],
            'status' => 'accepted',
            'type' => 'private',
        ]);

        $this->createSessionsForRequest($courseRequest);


        return response()->json(['data' => $courseRequest]);
    }

    /**
     * List all general course requests.
     */
    public function general(Request $request)
    {
        $courseRequests = CourseRequest::where('type', 'general')
            ->with(['learner', 'instructor', 'package'])
            ->get();

        return response()->json(['data' => $courseRequests]);
    }


    public function InstructorRequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $requests = CourseRequest::where('instructor_id', $validated['instructor_id'])
            ->with(['instructor'])
            ->get();

        return response()->json([
            'data' => $requests,
            'message' => 'Instructor Requests retrieved successfully.',
        ], 200);
    }

    public function LearnerRequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'learner_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $requests = CourseRequest::where('learner_id', $validated['learner_id'])
            ->with(['learner', 'instructor', 'package'])
            ->get();

        return response()->json([
            'data' => $requests,
            'message' => 'Learner Requests retrieved successfully.',
        ], 200);
    }

    public function accept(Request $request, CourseRequest $courseRequest)
    {
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        if ($courseRequest->instructor_id !== $validator->validated()['instructor_id'] || $courseRequest->status === 'accepted') {
            return response()->json(['message' => 'You cannot accept this request'], 403);
        }

        $courseRequest->update([
            'status' => 'accepted',
        ]);

        $this->createSessionsForRequest($courseRequest);
        

        return response()->json(['data' => $courseRequest]);
    }

    private function createSessionsForRequest(CourseRequest $request)
    {
        $days = $request->package->days_count ?? 0;

        if (!$days || !$request->start_date) {
            return;
        }

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $sessionStart = '09:00'; // or pull from config
        $sessionEnd = '11:00';   // or calculate dynamically

        for ($i = 0; $i < $days; $i++) {
            Session::create([
                'request_id' => $request->id,
                'date' => $startDate->copy()->addDays($i)->toDateString(),
                'start_time' => $sessionStart,
                'end_time' => $sessionEnd,
                'instructor_id' => $request->instructor_id,
                'learner_id' => $request->learner_id,
            ]);
        }
    }
}
