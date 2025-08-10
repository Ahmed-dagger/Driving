<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\RatingDataTable;
interface RatingRepositoryInterface {
    public function index(RatingDataTable $ratingDataTable);
    /*public function store($request);
    public function update($request);
    public function destroy($request);*/
}
