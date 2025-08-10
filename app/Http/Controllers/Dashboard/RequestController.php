<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\DataTables\Dashboard\Admin\RequestDataTable;
use App\Models\CourseRequest as Request; // Assuming CourseRequest is the model for requests
use App\Models\Package;
use App\Models\User;
use App\Repositories\Contracts\RequestRepositoryInterface;

class RequestController extends Controller
{
    public function __construct(
        protected RequestDataTable $requestDataTable,
        protected RequestRepositoryInterface $requestRepositoryInterface
    ) {}

    /**
     * Display a listing of requests.
     */
    public function index(RequestDataTable $requestDataTable)
    {
        return $this->requestRepositoryInterface->index($requestDataTable);
    }

    /**
     * Show the form for creating a new request.
     */
    public function create()
    {

        $learners = User::where('user_type', 'learner')->get();
        $instructors = User::where('user_type', 'instructor')->get();
        $packages = Package::all();


        return view('dashboard.Admin.requests.create', [
            'pageTitle' => trans('dashboard/admin.requests'),
            'learners' => $learners,
            'instructors' => $instructors,
            'packages' => $packages,
        ]);
    }

    /**
     * Store a newly created request in storage.
     */
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'learner_id'         => 'required|exists:users,id',
            'instructor_id'      => 'nullable|exists:users,id',
            'package_id'         => 'nullable|exists:packages,id',
            'start_date'         => 'nullable|date',
            'location_city'      => 'required|string|max:255',
            'location_area'      => 'required|string|max:255',
            'has_learner_car'    => 'boolean',
            'requires_transport' => 'boolean',
            'total_price'        => 'required|numeric|min:0',
            'type'               => 'required|in:general,private',
            'status'             => 'required|in:pending,accepted,rejected',
            'notes'              => 'nullable|string',
            'rejection_reason'   => 'nullable|string',
        ]);

        if ($request->filled('start_date')) {
            $validated['start_date'] = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d');
        }



        // Ensure boolean fields have default values
        $validated['has_learner_car'] = $request->boolean('has_learner_car');
        $validated['requires_transport'] = $request->boolean('requires_transport');

        Request::create($validated);

        return redirect()->route('admin.requests.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified request.
     */
    public function edit($id)
    {
        $requestItem = Request::withTrashed()->findOrFail($id);

        return view('dashboard.Admin.requests.edit', compact('requestItem'));
    }

    /**
     * Update the specified request in storage.
     */
    public function update(HttpRequest $request, $id)
    {
        $requestItem = Request::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'learner_id' => 'required|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'start_date' => 'nullable|date',
            'location_city' => 'required|string|max:255',
            'location_area' => 'required|string|max:255',
            'has_learner_car' => 'boolean',
            'requires_transport' => 'boolean',
            'total_price' => 'required|numeric|min:0',
            'type' => 'required|in:general,private',
            'status' => 'required|in:pending,accepted,rejected',
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $requestItem->update($validated);

        return redirect()->route('admin.requests.index')
            ->with('success', __('dashboard/messages.updated_successfully'));
    }

    /**
     * Soft-delete the specified request.
     */
    public function destroy($id)
    {
        $requestItem = Request::findOrFail($id);
        $requestItem->delete();

        return redirect()->route('admin.requests.index')
            ->with('success', __('dashboard/messages.deleted_successfully'));
    }

    /**
     * Restore the soft-deleted request.
     */
    public function restore($id)
    {
        $requestItem = Request::withTrashed()->findOrFail($id);
        $requestItem->restore();

        return redirect()->route('admin.requests.index')
            ->with('success', __('dashboard/messages.restored_successfully'));
    }
}
