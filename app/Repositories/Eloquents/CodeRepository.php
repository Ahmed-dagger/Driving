<?php
namespace  App\Repositories\Eloquents;
use App\Models\Category;
use App\Repositories\Contracts\CodeRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\CodeDataTable;

class CodeRepository implements CodeRepositoryInterface {
    public function index(CodeDataTable $codeDataTable) {

        return $codeDataTable->render('dashboard.Admin.codes.index', ['pageTitle' => trans('dashboard/admin.codes')]);

    }

    public function store($request) {

    }

    public function update($request) {

    }

    public function destroy($request) {

    }
}
