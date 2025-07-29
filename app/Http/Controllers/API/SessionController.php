<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with(['instructor', 'courseRequest'])->get();
        return response()->json(['data' => $sessions]);
    }

    public function show(Session $session)
    {
        $session->load(['instructor', 'courseRequest']);
        return response()->json(['data' => $session]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_request_id' => 'required|exists:course_requests,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = Session::create($validator->validated());
        return response()->json(['data' => $session], 201);
    }

    public function update(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,completed,rejected,canceled',
            'rejection_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session->update($validator->validated());
        return response()->json(['data' => $session]);
    }

    public function destroy(Session $session)
    {
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully.']);
    }

    public function markCompleted(Session $session)
    {
        $session->update([
            'status' => Session::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return response()->json(['data' => $session, 'message' => 'Session marked as completed.']);
    }

    public function markRejected(Request $request, Session $session)
    {
        if ($session->status === Session::STATUS_COMPLETED) {
            return response()->json(['message' => 'this session is already completed'], 400);
        }

        $session->update([
            'status' => Session::STATUS_REJECTED,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return response()->json(['data' => $session, 'message' => 'Session marked as completed.']);
    }

        public function markCanceled(Request $request, Session $session)
    {
        if ($session->status !== Session::STATUS_PENDING) {
            return response()->json(['message' => 'this session can not be canceled'], 400);
        }

        $session->update([
            'status' => Session::STATUS_CANCELED,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return response()->json(['data' => $session, 'message' => 'Session marked as completed.']);
    }

    public function rate(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session->update([
            'rate' => $request->rate,
            'notes' => $request->notes,
        ]);
        $session->save();

        return response()->json(['data' => $session, 'message' => 'Session rated successfully.']);
    }

    public function getByRequestId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|exists:requests,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sessions = Session::with(['instructor', 'courseRequest'])
            ->where('request_id', $request->request_id)
            ->get();

        return response()->json(['data' => $sessions]);
    }
}
