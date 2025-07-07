<?php

namespace  App\Repositories\Eloquents;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\AdminDataTable;

class AdminRepository implements AdminRepositoryInterface
{
    public function index(AdminDataTable $adminDataTable)
    {
        return $adminDataTable->render('dashboard.Admin.admins.index', ['pageTitle' => trans('dashboard/admin.admins')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
