<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'learner_id' => 'required|exists:users,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Prevent duplicate rating (optional)
        $existing = Rating::where('instructor_id', $validated['instructor_id'])
            ->where('learner_id', $validated['learner_id'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'You have already rated this instructor.'], 409);
        }

        $rating = Rating::create($validated);

        $this->updateInstructorAverage($validated['instructor_id']);

        return response()->json($rating, 201);
    }

    // PUT /ratings/{id}
    public function update(Request $request, Rating $rating)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating->update($validated);

        $this->updateInstructorAverage($rating->instructor_id);

        return response()->json($rating);
    }

    // GET /ratings/instructor/{id}
    public function getInstructorRatings($id)
    {
        $ratings = Rating::where('instructor_id', $id)
            ->with('learner:id,name') // optional: load learner info
            ->latest()
            ->get(['id', 'learner_id', 'rating', 'comment', 'created_at']);

        return response()->json($ratings);
    }


    // Utility: Recalculate average
    protected function updateInstructorAverage($instructorId)
    {
        $instructor = User::findOrFail($instructorId);
        $avg = Rating::where('instructor_id', $instructorId)->avg('rating');
        $instructor->rate = round($avg, 2); // 2 decimal precision
        $instructor->save();
    }
}
