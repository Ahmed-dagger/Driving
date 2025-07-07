<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\AdminDataTable;
interface AdminRepositoryInterface {
    public function index(AdminDataTable $adminDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
