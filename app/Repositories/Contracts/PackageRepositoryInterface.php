<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\PackageDataTable;
interface PackageRepositoryInterface {
    public function index(PackageDataTable $packageDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
