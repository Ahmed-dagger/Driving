<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\DataTables\Dashboard\Admin\RatingDataTable;
use App\Models\Rating;
use App\Models\User;
use App\Repositories\Contracts\RatingRepositoryInterface;

class RatingController extends Controller
{
    public function __construct(
        protected RatingDataTable $ratingDataTable,
        protected RatingRepositoryInterface $ratingRepositoryInterface
    ) {}

    /**
     * Display a listing of ratings.
     */
    public function index(RatingDataTable $ratingDataTable)
    {
        return $this->ratingRepositoryInterface->index($ratingDataTable);
    }

    /**
     * Show the form for creating a new rating.
     */
    public function create()
    {
        $learners = User::where('user_type', 'learner')->get();
        $instructors = User::where('user_type', 'instructor')->get();

        return view('dashboard.Admin.ratings.create', [
            'pageTitle' => trans('dashboard/admin.ratings'),
            'learners' => $learners,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a newly created rating in storage.
     */
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'learner_id'    => 'required|exists:users,id',
            'rating'        => 'required|numeric|min:0|max:5',
            'comment'       => 'nullable|string',
        ]);

        Rating::create($validated);

        return redirect()->route('admin.ratings.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified rating.
     */
    public function edit($id)
    {
        $ratingItem = Rating::findOrFail($id);

        return view('dashboard.Admin.ratings.edit', compact('ratingItem'));
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(HttpRequest $request, $id)
    {
        $ratingItem = Rating::findOrFail($id);

        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'learner_id'    => 'required|exists:users,id',
            'rating'        => 'required|numeric|min:0|max:5',
            'comment'       => 'nullable|string',
        ]);

        $ratingItem->update($validated);

        return redirect()->route('admin.ratings.index')
            ->with('success', __('dashboard/messages.updated_successfully'));
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy($id)
    {
        $ratingItem = Rating::findOrFail($id);
        $ratingItem->delete();

        return redirect()->route('admin.ratings.index')
            ->with('success', __('dashboard/messages.deleted_successfully'));
    }
}
