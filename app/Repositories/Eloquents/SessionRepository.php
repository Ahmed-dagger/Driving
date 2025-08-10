<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\SessionDataTable;
use App\Repositories\Contracts\SessionRepositoryInterface;
class SessionRepository implements SessionRepositoryInterface
{
    public function index(SessionDataTable $sessionDataTable)
    {
        return $sessionDataTable->render('dashboard.Admin.sessions.index', ['pageTitle' => trans('dashboard/admin.sessions')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
