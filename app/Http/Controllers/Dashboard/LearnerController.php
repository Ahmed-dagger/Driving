<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\LearnerDataTable;
use App\Models\User;
use App\Repositories\Contracts\LearnerRepositoryInterface;

class LearnerController extends Controller
{
    public function __construct(protected LearnerDataTable $learnerDataTable, protected LearnerRepositoryInterface $learnerRepositoryInterface)
    {
        $this->learnerRepositoryInterface = $learnerRepositoryInterface;
        $this->learnerDataTable = $learnerDataTable;
    }

    /**
     * Display a listing of learners.
     */
    public function index(LearnerDataTable $learnerDataTable)
    {
        return $this->learnerRepositoryInterface->index($this->learnerDataTable);
    }

    /**
     * Show the form for creating a new learner.
     */
    public function create()
    {
        return view('dashboard.Admin.learners.create', [
            'pageTitle' => trans('dashboard/admin.learners'),
        ]);
    }

    /**
     * Store a newly created learner.
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
        $data['user_type'] = 'learner';

        User::create($data);

        return redirect()->route('admin.learners.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }


    public function edit($id)
    {
        $learner = User::withTrashed()->findOrFail($id);
        return view('dashboard.Admin.learners.edit', compact('learner'));
    }

    public function update(Request $request, $id)
    {
        $learner = User::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $learner->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $learner->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('admin.learners.index')->with('success', __('dashboard/messages.updated_successfully'));
    }

    public function destroy($id)
    {
        $learner = User::findOrFail($id);
        $learner->delete();

        return redirect()->route('admin.learners.index')->with('success', __('dashboard/messages.deleted_successfully'));
    }

    public function restore($id)
    {
        $learner = User::withTrashed()->findOrFail($id);
        $learner->restore();

        return redirect()->route('admin.learners.index')->with('success', __('dashboard/messages.restored_successfully'));
    }
}
