<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\PackageDataTable;
use App\Models\Package;
use App\Repositories\Contracts\PackageRepositoryInterface;

class PackageController extends Controller
{
    public function __construct(protected PackageDataTable $packageDataTable, protected PackageRepositoryInterface $packageRepositoryInterface)
    {
        $this->packageRepositoryInterface = $packageRepositoryInterface;
        $this->packageDataTable = $packageDataTable;
    }

    /**
     * Display a listing of packages.
     */
    public function index(PackageDataTable $packageDataTable)
    {
        return $this->packageRepositoryInterface->index($this->packageDataTable);
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('dashboard.Admin.packages.create', [
            'pageTitle' => trans('dashboard/admin.packages'),
        ]);
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days_count' => 'required|integer|min:1',
            'hours_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'days_count', 'hours_count', 'price']);

        Package::create($data);

        return redirect()->route('admin.packages.index')
            ->with('success', trans('dashboard/messages.created_successfully'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit($id)
    {
        $package = Package::withTrashed()->findOrFail($id);

        return view('dashboard.Admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, $id)
    {
        $package = Package::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days_count' => 'required|integer|min:1',
            'hours_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $package->update($request->only(['name', 'description', 'days_count', 'hours_count', 'price']));

        return redirect()->route('admin.packages.index')
            ->with('success', __('dashboard/messages.updated_successfully'));
    }

    /**
     * Remove the specified package.
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', __('dashboard/messages.deleted_successfully'));
    }

    /**
     * Restore the specified package.
     */
    public function restore($id)
    {
        $package = Package::withTrashed()->findOrFail($id);
        $package->restore();

        return redirect()->route('admin.packages.index')
            ->with('success', __('dashboard/messages.restored_successfully'));
    }
}
