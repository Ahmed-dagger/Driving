<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\Session;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class SessionDataTable extends BaseDataTable
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
                        window.location.href = "' . route('admin.sessions.index', ["filter" => "deleted"]) . '";
                    }
                ',
            ];
        } elseif (request()->input('filter') === 'deleted') {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-list'></i> " . trans('dashboard/datatable.sessions'),
                'className' => 'btn btn-primary',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.sessions.index') . '";
                    }
                ',
            ];
        }

        return $parameters;
    }

    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Session());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Session $session) {
                return view('dashboard.Admin.sessions.btn.actions', compact('session'));
            })
            ->editColumn('date', fn($session) => $this->formatBadge(optional($session->date)->format('M j, Y')))
            ->editColumn('start_time', fn($session) => optional($session->start_time)->format('H:i'))
            ->editColumn('end_time', fn($session) => optional($session->end_time)->format('H:i'))
            ->editColumn('status', function ($session) {
                return '<span class="badge badge-' . $this->statusColor($session->status) . '">' . trans("dashboard/admin.{$session->status}") . '</span>';
            })
            ->editColumn('learner', fn($session) => optional($session->learner)->name)
            ->editColumn('instructor', fn($session) => optional($session->instructor)->name ?? trans('dashboard/admin.general_request'))
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
            ->rawColumns(['action', 'date', 'status']);
    }

    public function query(): QueryBuilder
    {
        return Session::query()
            ->withTrashed()
            ->with(['learner', 'instructor', 'courseRequest']);
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#' , 'orderable' => true , 'searchable' => true],
            ['name' => 'learner', 'data' => 'learner', 'title' => trans('dashboard/admin.learner')],
            ['name' => 'instructor', 'data' => 'instructor', 'title' => trans('dashboard/admin.instructor')],
            ['name' => 'date', 'data' => 'date', 'title' => trans('dashboard/admin.date')],
            ['name' => 'start_time', 'data' => 'start_time', 'title' => trans('dashboard/admin.start_time')],
            ['name' => 'end_time', 'data' => 'end_time', 'title' => trans('dashboard/admin.end_time')],
            ['name' => 'price', 'data' => 'price', 'title' => trans('dashboard/admin.price')],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/admin.status')],
            ['name' => 'notes', 'data' => 'notes', 'title' => trans('dashboard/admin.notes')],
            ['name' => 'completed_at', 'data' => 'completed_at', 'title' => trans('dashboard/admin.completed_at')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at')],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at')],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }

    private function statusColor($status)
    {
        return match ($status) {
            Session::STATUS_PENDING => 'warning',
            Session::STATUS_COMPLETED => 'success',
            Session::STATUS_REJECTED => 'danger',
            Session::STATUS_CANCELED => 'secondary',
            default => 'secondary',
        };
    }
}
