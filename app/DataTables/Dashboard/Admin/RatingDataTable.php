<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\Rating;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class RatingDataTable extends BaseDataTable
{
    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Rating());
        $this->request = $request;
    }

    protected function getParameters()
    {
        $parameters = parent::getParameters();
        return $parameters; // No deleted filter since ratings don't use SoftDeletes
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Rating $rating) {
                return view('dashboard.Admin.ratings.btn.actions', compact('rating'));
            })
            ->editColumn('instructor', fn($rating) => optional($rating->instructor)->name ?? '-')
            ->editColumn('learner', fn($rating) => optional($rating->learner)->name ?? '-')
            ->editColumn('rating', fn($rating) => number_format($rating->rating, 1))
            ->editColumn('comment', fn($rating) => $rating->comment ?: '-')
            ->filterColumn('learner', function ($query, $keyword) {
                $query->whereHas('learner', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . strtolower($keyword) . '%');
                });
            })
            ->filterColumn('instructor', function ($query, $keyword) {
                $query->whereHas('instructor', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . strtolower($keyword) . '%');
                });
            })
            ->rawColumns(['action']);
    }

    public function query(): QueryBuilder
    {
        return Rating::query()
            ->with(['learner', 'instructor']);
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => true, 'searchable' => true],
            ['name' => 'learner', 'data' => 'learner', 'title' => trans('dashboard/admin.learner')],
            ['name' => 'instructor', 'data' => 'instructor', 'title' => trans('dashboard/admin.instructor')],
            ['name' => 'rating', 'data' => 'rating', 'title' => trans('dashboard/admin.rating')],
            ['name' => 'comment', 'data' => 'comment', 'title' => trans('dashboard/admin.comment')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at')],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at')],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
