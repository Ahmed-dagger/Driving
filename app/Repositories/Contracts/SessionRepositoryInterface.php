<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\SessionDataTable;
interface SessionRepositoryInterface {
    public function index(SessionDataTable $sessionDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
