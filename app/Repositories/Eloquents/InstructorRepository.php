<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\InstructorDataTable;
use App\DataTables\Dashboard\Admin\LearnerDataTable;
use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use App\Repositories\Contracts\InstructorRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use App\Repositories\Contracts\LearnerRepositoryInterface;

class InstructorRepository implements InstructorRepositoryInterface
{
    public function index(InstructorDataTable $instructorDataTable)
    {
        return $instructorDataTable->render('dashboard.Admin.instructors.index', ['pageTitle' => trans('dashboard/admin.instructors')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
