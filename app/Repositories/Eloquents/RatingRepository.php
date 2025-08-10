<?php

namespace  App\Repositories\Eloquents;

use App\DataTables\Dashboard\Admin\RatingDataTable;
use App\Repositories\Contracts\RatingRepositoryInterface;
class RatingRepository implements RatingRepositoryInterface
{
    public function index(RatingDataTable $ratingDataTable)
    {
        return $ratingDataTable->render('dashboard.Admin.ratings.index', ['pageTitle' => trans('dashboard/admin.ratings')]);
    }

    public function store($request) {}

    public function update($request) {}

    public function destroy($request) {}
}
