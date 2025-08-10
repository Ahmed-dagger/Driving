<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\CourseRequest as Request;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class RequestDataTable extends BaseDataTable
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
                        window.location.href = "' . route('admin.requests.index', ["filter" => "deleted"]) . '";
                    }
                ',
            ];
        } elseif (request()->input('filter') === 'deleted') {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-list'></i> " . trans('dashboard/datatable.requests'),
                'className' => 'btn btn-primary',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.requests.index') . '";
                    }
                ',
            ];
        }

        return $parameters;
    }

    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Request());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Request $request) {
                return view('dashboard.Admin.requests.btn.actions', compact('request'));
            })
            ->editColumn('created_at', fn($request) => $this->formatBadge(optional($request->created_at)->format('M j, Y')))
            ->editColumn('updated_at', fn($request) => $this->formatBadge(optional($request->updated_at)->format('M j, Y')))
            ->editColumn('start_date', fn($request) => $this->formatBadge(optional($request->start_date)->format('M j, Y')))
            ->editColumn('type', fn($request) => ucfirst($request->type))
            ->editColumn('status', function ($request) {
                return '<span class="badge badge-' . $this->statusColor($request->status) . '">' . trans("dashboard/admin.{$request->status}") . '</span>';
            })
            ->editColumn('learner', fn($request) => optional($request->learner)->name)
            ->editColumn('instructor', fn($request) => optional($request->instructor)->name ?? trans('dashboard/admin.general_request'))
            ->editColumn('package', fn($request) => optional($request->package)->name ?? '-')
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
            ->filterColumn('package', function ($query, $keyword) {
                $query->whereHas('package', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . strtolower($keyword) . '%');
                });
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'start_date', 'status']);
    }


    public function query(): QueryBuilder
    {
        return Request::query()
            ->withTrashed()
            ->with(['learner', 'instructor', 'package']);
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#'],
            ['name' => 'learner', 'data' => 'learner', 'title' => trans('dashboard/admin.learner')],
            ['name' => 'instructor', 'data' => 'instructor', 'title' => trans('dashboard/admin.instructor')],
            ['name' => 'package', 'data' => 'package', 'title' => trans('dashboard/admin.package')],
            ['name' => 'start_date', 'data' => 'start_date', 'title' => trans('dashboard/admin.start_date')],
            ['name' => 'location_city', 'data' => 'location_city', 'title' => trans('dashboard/admin.city')],
            ['name' => 'location_area', 'data' => 'location_area', 'title' => trans('dashboard/admin.area')],
            ['name' => 'has_learner_car', 'data' => 'has_learner_car', 'title' => trans('dashboard/admin.has_learner_car')],
            ['name' => 'requires_transport', 'data' => 'requires_transport', 'title' => trans('dashboard/admin.requires_transport')],
            ['name' => 'total_price', 'data' => 'total_price', 'title' => trans('dashboard/admin.total_price')],
            ['name' => 'type', 'data' => 'type', 'title' => trans('dashboard/admin.type')],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/admin.status')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at')],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at')],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }

    private function statusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
