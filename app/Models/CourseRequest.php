<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'learner_id',
        'instructor_id',
        'package_id',
        'requested_start_date',
        'location_city',
        'location_area',
        'has_learner_car',
        'requires_transport',
        'total_price',
        'type',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'has_learner_car' => 'boolean',
        'requires_transport' => 'boolean',
        'requested_start_date' => 'date',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'request_id'); // specify the correct foreign key
    }
}
