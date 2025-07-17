<?php

namespace App\DataTables\Dashboard\Admin;

use App\Models\User;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class InstructorDataTable extends BaseDataTable
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
                        window.location.href = "' . route('admin.instructors.index', ["filter" => "deleted"]) . '";
                    }
                ',
            ];
        } elseif (request()->input('filter') === 'deleted') {
            $parameters['buttons'][] = [
                'text' => "<i class='fa fa-user'></i> " . trans('dashboard/datatable.instructors'),
                'className' => 'btn btn-primary',
                'action' => '
                    function(e, dt, node, config) {
                        window.location.href = "' . route('admin.instructors.index') . '";
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
            ->addColumn('action', function (User $instructor) {
                return view('dashboard.Admin.instructors.btn.actions', compact('instructor'));
            })
            ->editColumn('created_at', function (User $instructor) {
                return $this->formatBadge($this->formatDate($instructor->created_at));
            })
            ->editColumn('updated_at', function (User $instructor) {
                return $this->formatBadge($this->formatDate($instructor->updated_at));
            })
            ->editColumn('deleted_at', function (User $instructor) {
                return $this->formatBadge($this->formatDate($instructor->deleted_at));
            })
            ->editColumn('name', function (User $instructor) {
                return '<a href="' . route('admin.instructors.show', $instructor->id) . '">' . e($instructor->name) . '</a>';
            })
            ->editColumn('status', function (User $instructor) {
                return $this->formatStatus($instructor->status);
            })
            ->addColumn('profile_image', function (User $instructor) {
                $url = $instructor->getFirstMediaUrl('profile_images');
                if ($url) {
                    return '<a href="' . $url . '" data-fancybox="profile-' . $instructor->id . '" data-caption="Profile Picture">
                                <img src="' . $url . '" alt="Profile" style="width:50px;height:50px;border-radius:50%;">
                            </a>';
                }
                return '-';
            })
            ->addColumn('license_images', function (User $instructor) {
                $url = $instructor->getFirstMediaUrl('license_images');
                if ($url) {
                    return '<a href="' . $url . '" data-fancybox="license-' . $instructor->id . '" data-caption="License Picture">
                                <img src="' . $url . '" alt="License" style="width:70px;height:50px;">
                           </a>';
                }
                return '-';
            })

            ->addColumn('car_image', function (User $instructor) {
                $url = $instructor->getFirstMediaUrl('car_images');
                if ($url) {
                    return '<a href="' . $url . '" data-fancybox="car-' . $instructor->id . '" data-caption="Car Picture">
                                <img src="' . $url . '" alt="Car" style="width:70px;height:50px;">
                           </a>';
                }
                return '-';
            })

            ->rawColumns(['action', 'created_at', 'updated_at', 'deleted_at', 'status', 'name', 'profile_image', 'car_image', 'license_images']);
    }

    public function query(): QueryBuilder
    {
        return User::query()
            ->where('user_type', 'instructor')
            ->withTrashed();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false],
            ['name' => 'profile_image', 'data' => 'profile_image', 'title' => trans('dashboard/admin.profile_picture'), 'orderable' => false, 'searchable' => false],
            ['name' => 'car_image', 'data' => 'car_image', 'title' => trans('dashboard/admin.car_picture'), 'orderable' => false, 'searchable' => false],
            ['name' => 'license_images', 'data' => 'license_images', 'title' => trans('dashboard/admin.license_picture'), 'orderable' => false, 'searchable' => false],
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
