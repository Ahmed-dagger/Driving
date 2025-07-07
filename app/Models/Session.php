<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = [
        'learner_id',
        'instructor_id',
        'package_id',
        'date',
        'start_time',
        'end_time',
        'location_city',
        'location_area',
        'has_learner_car',
        'requires_transport',
        'price',
        'status',
        'rejection_reason',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'has_learner_car' => 'boolean',
        'requires_transport' => 'boolean',
        'completed_at' => 'datetime',
        'date' => 'date',
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
        return $this->belongsTo(Package::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function requests()
    {
        return $this->hasMany(SessionRequest::class);
    }
}
