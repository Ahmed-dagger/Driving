<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\LearnerDataTable;
use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use App\Repositories\Contracts\LearnerRepositoryInterface;

class LearnerRepository implements LearnerRepositoryInterface
{
    public function index(LearnerDataTable $learnerDataTable)
    {
        return $learnerDataTable->render('dashboard.Admin.learners.index', ['pageTitle' => trans('dashboard/admin.learners')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
