<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use App\DataTables\Dashboard\Admin\LearnerDataTable;
interface LearnerRepositoryInterface {
    public function index(LearnerDataTable $learnerDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
