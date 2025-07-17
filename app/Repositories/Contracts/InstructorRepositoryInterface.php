<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\InstructorDataTable;
interface InstructorRepositoryInterface {
    public function index(InstructorDataTable $instructorDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
