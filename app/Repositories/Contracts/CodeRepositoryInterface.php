<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\CodeDataTable;
interface CodeRepositoryInterface {
    public function index(CodeDataTable $codeDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
