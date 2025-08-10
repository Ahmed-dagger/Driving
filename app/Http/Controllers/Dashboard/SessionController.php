<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\DataTables\Dashboard\Admin\SessionDataTable;
use App\Models\Session;
use App\Models\User;
use App\Repositories\Contracts\SessionRepositoryInterface;

class SessionController extends Controller
{
    public function __construct(
        protected SessionDataTable $sessionDataTable,
        protected SessionRepositoryInterface $sessionRepositoryInterface
    ) {}

    /**
     * Display a listing of sessions.
     */
    public function index(SessionDataTable $sessionDataTable)
    {
        return $this->sessionRepositoryInterface->index($sessionDataTable);
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $learners = User::where('user_type', 'learner')->get();
        $instructors = User::where('user_type', 'instructor')->get();

        return view('dashboard.Admin.sessions.create', [
            'pageTitle' => trans('dashboard/admin.sessions'),
            'learners' => $learners,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'request_id'       => 'nullable|exists:course_requests,id',
            'instructor_id'    => 'required|exists:users,id',
            'learner_id'       => 'required|exists:users,id',
            'date'             => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'price'            => 'required|numeric|min:0',
            'status'           => 'required|in:pending,completed,rejected,canceled',
            'notes'            => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'completed_at'     => 'nullable|date',
            'rate'             => 'nullable|numeric|min:0|max:5',
        ]);

        Session::create($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit($id)
    {
        $sessionItem = Session::withTrashed()->findOrFail($id);

        return view('dashboard.Admin.sessions.edit', compact('sessionItem'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(HttpRequest $request, $id)
    {
        $sessionItem = Session::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'request_id'       => 'nullable|exists:requests,id',
            'instructor_id'    => 'required|exists:users,id',
            'learner_id'       => 'required|exists:users,id',
            'date'             => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'price'            => 'required|numeric|min:0',
            'status'           => 'required|in:pending,completed,rejected,canceled',
            'notes'            => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'completed_at'     => 'nullable|date',
            'rate'             => 'nullable|numeric|min:0|max:5',
        ]);

        $sessionItem->update($validated);

        return redirect()->route('admin.sessions.index')
            ->with('success', __('dashboard/messages.updated_successfully'));
    }

    /**
     * Soft-delete the specified session.
     */
    public function destroy($id)
    {
        $sessionItem = Session::findOrFail($id);
        $sessionItem->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', __('dashboard/messages.deleted_successfully'));
    }

    /**
     * Restore the soft-deleted session.
     */
    public function restore($id)
    {
        $sessionItem = Session::withTrashed()->findOrFail($id);
        $sessionItem->restore();

        return redirect()->route('admin.sessions.index')
            ->with('success', __('dashboard/messages.restored_successfully'));
    }
}
