<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\Package;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class PackageDataTable extends BaseDataTable
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
                        window.location.href = "' . route('admin.packages.index', ["filter" => "deleted"]) . '";
                    }
                ',
            ];
        } elseif (request()->input('filter') === 'deleted') {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-box'></i> " . trans('dashboard/datatable.packages'),
                'className' => 'btn btn-primary',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.packages.index') . '";
                    }
                ',
            ];
        }

        return $parameters;
    }

    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Package());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Package $package) {
                return view('dashboard.Admin.packages.btn.actions', compact('package'));
            })
            ->editColumn('created_at', function (Package $package) {
                return $this->formatBadge($this->formatDate($package->created_at));
            })
            ->editColumn('updated_at', function (Package $package) {
                return $this->formatBadge($this->formatDate($package->updated_at));
            })
            ->editColumn('deleted_at', function (Package $package) {
                return $this->formatBadge($this->formatDate($package->deleted_at));
            })
            ->editColumn('name', function (Package $package) {
                return '<a href="' . route('admin.packages.show', $package->id) . '">' . e($package->name) . '</a>';
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'deleted_at', 'name']);
    }

    public function query(): QueryBuilder
    {
        return Package::query()
            ->withTrashed();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name')],
            ['name' => 'description', 'data' => 'description', 'title' => trans('dashboard/admin.description')],
            ['name' => 'days_count', 'data' => 'days_count', 'title' => trans('dashboard/admin.days_count')],
            ['name' => 'hours_count', 'data' => 'hours_count', 'title' => trans('dashboard/admin.hours_count')],
            ['name' => 'price', 'data' => 'price', 'title' => trans('dashboard/admin.price')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'deleted_at', 'data' => 'deleted_at', 'title' => trans('dashboard/general.deleted_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
