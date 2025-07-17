<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\PackageDataTable;
use App\Repositories\Contracts\PackageRepositoryInterface;
class PackageRepository implements PackageRepositoryInterface
{
    public function index(PackageDataTable $packageDataTable)
    {
        return $packageDataTable->render('dashboard.Admin.packages.index', ['pageTitle' => trans('dashboard/admin.packages')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
