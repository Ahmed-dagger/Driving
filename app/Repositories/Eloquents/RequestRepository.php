<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\RequestDataTable;
use App\Repositories\Contracts\RequestRepositoryInterface;
class RequestRepository implements RequestRepositoryInterface
{
    public function index(RequestDataTable $requestDataTable)
    {
        return $requestDataTable->render('dashboard.Admin.requests.index', ['pageTitle' => trans('dashboard/admin.requests')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
