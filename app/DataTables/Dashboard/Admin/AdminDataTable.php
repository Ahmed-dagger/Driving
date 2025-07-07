<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\Admin;
use App\DataTables\Base\BaseDataTable;
use Flasher\Laravel\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class AdminDataTable extends BaseDataTable
{

    protected function getParameters() {

        $parameters = parent::getParameters();

        if(!request()->has('filter'))
        {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-trash'></i> " . trans('dashboard/datatable.deleted'),
                'className' => 'btn btn-danger',
                'action' => '
                function(e, dt, node, config) {
                 window.location.href = "' . route('admin.admins.index' , ["filter"=>"deleted"]) . '"; } 
                 '
            ];
        }

        elseif(request()->has('filter') && request()->input('filter') === 'deleted')
        {
            $parameters['buttons'][] = [
            'text' => "<i class='fa fa-user'></i> " . trans('dashboard/datatable.admins'),
            'className' => 'btn btn-primary',
            'action' => '
            function(e, dt, node, config) {
             window.location.href = "' . route('admin.admins.index') . '"; } 
             '
        ];
        }

        
        return $parameters;
    }

    
    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Admin());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Admin $admin) {
                return view('dashboard.admin.admins.btn.actions', compact('admin'));
            })
            ->editColumn('created_at', function (Admin $admin) {
                return $this->formatBadge($this->formatDate($admin->created_at));
            })
            ->editColumn('updated_at', function (Admin $admin) {
                return $this->formatBadge($this->formatDate($admin->updated_at));
            })
            ->editColumn('deleted_at', function (Admin $admin) {
                return $this->formatBadge($this->formatDate($admin->deleted_at));
            })
            ->editColumn('name', function (Admin $admin) {
                return '<a href="' . route('admin.admins.show', $admin->profile->uuid) . '">' . $admin->name . '</a>';
            })
            ->editColumn('status', function (Admin $admin) {
                return $this->formatStatus($admin->status);
            })
            ->rawColumns(['action', 'created_at', 'updated_at','deleted_at', 'status', 'name']);
    }

    public function query(): QueryBuilder
    {
        

        if(request()->has('filter') && request()->input('filter') === 'deleted')
        {
            return Admin::query()->whereNot('id', get_user_data()->id)->onlyTrashed();
        }
        return Admin::query()->whereNot('id', get_user_data()->id);
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'email', 'data' => 'email', 'title' => trans('dashboard/admin.email'),],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/general.status'),],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'deleted_at', 'data' => 'deleted_at', 'title' => trans('dashboard/general.deleted_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}
