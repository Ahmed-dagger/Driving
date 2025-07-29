<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = [
        'request_id',
        'instructor_id',
        'learner_id',
        'date',
        'start_time',
        'end_time',
        'price',
        'status',
        'notes',
        'rejection_reason',
        'completed_at',
        'rate',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'completed_at' => 'datetime',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function courseRequest()
    {
        return $this->belongsTo(CourseRequest::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELED = 'canceled';
}
