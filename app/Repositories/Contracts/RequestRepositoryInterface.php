<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\RequestDataTable;
interface RequestRepositoryInterface {
    public function index(RequestDataTable $requestDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
