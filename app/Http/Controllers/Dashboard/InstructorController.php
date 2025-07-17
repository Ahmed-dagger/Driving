<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\InstructorDataTable;
use App\Models\User;
use App\Repositories\Contracts\InstructorRepositoryInterface;

class InstructorController extends Controller
{
    public function __construct(
        protected InstructorDataTable $instructorDataTable,
        protected InstructorRepositoryInterface $instructorRepositoryInterface
    ) {
        $this->instructorRepositoryInterface = $instructorRepositoryInterface;
        $this->instructorDataTable = $instructorDataTable;
    }

    /**
     * Display a listing of instructors.
     */
    public function index(InstructorDataTable $instructorDataTable)
    {
        return $this->instructorRepositoryInterface->index($this->instructorDataTable);
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('dashboard.Admin.instructors.create', [
            'pageTitle' => trans('dashboard/admin.instructors'),
        ]);
    }

    /**
     * Store a newly created instructor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'bio' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        $data['password'] = bcrypt($request->password);
        $data['status'] = 'active';
        $data['user_type'] = 'instructor';

        User::create($data);

        return redirect()->route('admin.instructors.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit($id)
    {
        $instructor = User::withTrashed()->findOrFail($id);
        return view('dashboard.Admin.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified instructor.
     */
    public function update(Request $request, $id)
    {
        $instructor = User::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $instructor->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $instructor->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('admin.instructors.index')->with('success', __('dashboard/messages.updated_successfully'));
    }

    /**
     * Remove the specified instructor.
     */
    public function destroy($id)
    {
        $instructor = User::findOrFail($id);
        $instructor->delete();

        return redirect()->route('admin.instructors.index')->with('success', __('dashboard/messages.deleted_successfully'));
    }

    /**
     * Restore the specified instructor.
     */
    public function restore($id)
    {
        $instructor = User::withTrashed()->findOrFail($id);
        $instructor->restore();

        return redirect()->route('admin.instructors.index')->with('success', __('dashboard/messages.restored_successfully'));
    }
}
