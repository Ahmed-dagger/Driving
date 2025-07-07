<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\Admin;

use App\DataTables\Base\BaseDataTable;
use App\Models\Course;
use App\Models\Playlist;
use Flasher\Laravel\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class CourseDataTable extends BaseDataTable
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
        parent::__construct(new Course());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Course $course) {
                return view('dashboard.admin.courses.btn.actions', compact('course'));
            })
            ->editColumn('created_at', function (Course $course) {
                return $this->formatBadge($this->formatDate(value: $course->created_at));
            })
            ->editColumn('updated_at', function (Course $course) {
                return $this->formatBadge($this->formatDate($course->updated_at));
            })
            ->editColumn('deleted_at', function (Course $course) {
                return $this->formatBadge($this->formatDate($course->deleted_at));
            })
            ->editColumn('status', function (Course $course) {
                return $this->formatStatus($course->status);
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'deleted_at', 'status', 'name']);
    }

    public function query(): QueryBuilder
    {
        return Course::query();
    }

    public function getColumns(): array
    {
        

        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name')],
            ['name' => 'desc', 'data' => 'desc', 'title' => trans('dashboard/admin.desc')],
            ['name' => 'price', 'data' => 'Price', 'title' => trans('dashboard/admin.price')],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'deleted_at', 'data' => 'deleted_at', 'title' => trans('dashboard/general.deleted_at'), 'orderable' => false, 'searchable' => false],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
