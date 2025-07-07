<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\User;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class LearnerDataTable extends BaseDataTable
{
    protected function getParameters()
    {
        $parameters = parent::getParameters();

        if (!request()->has('filter')) {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-trash'></i> " . trans('dashboard/datatable.deleted'),
                'className' => 'btn btn-danger',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.learners.index', ["filter" => "deleted"]) . '";
                    }
                ',
            ];
        } elseif (request()->input('filter') === 'deleted') {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-user'></i> " . trans('dashboard/datatable.learners'),
                'className' => 'btn btn-primary',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.learners.index') . '";
                    }
                ',
            ];
        }

        return $parameters;
    }

    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new User());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (User $learner) {
                return view('dashboard.Admin.learners.btn.actions', compact('learner'));
            })
            ->editColumn('created_at', function (User $learner) {
                return $this->formatBadge($this->formatDate($learner->created_at));
            })
            ->editColumn('updated_at', function (User $learner) {
                return $this->formatBadge($this->formatDate($learner->updated_at));
            })
            ->editColumn('deleted_at', function (User $learner) {
                return $this->formatBadge($this->formatDate($learner->deleted_at));
            })
            ->editColumn('name', function (User $learner) {
                return '<a href="' . route('admin.learners.show', $learner->id) . '">' . e($learner->name) . '</a>';
            })
            ->editColumn('status', function (User $learner) {
                return $this->formatStatus($learner->status);
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'deleted_at', 'status', 'name']);
    }

    public function query(): QueryBuilder
    {
        return User::query()
            ->where('user_type', 'learner')
            ->withTrashed(); // If you want to include soft-deleted learners
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name')],
            ['name' => 'email', 'data' => 'email', 'title' => trans('dashboard/admin.email')],
            ['name' => 'phone', 'data' => 'phone', 'title' => trans('dashboard/admin.phone')],
            ['name' => 'bio', 'data' => 'bio', 'title' => trans('dashboard/admin.bio')],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/general.status')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'deleted_at', 'data' => 'deleted_at', 'title' => trans('dashboard/general.deleted_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
