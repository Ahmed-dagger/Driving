<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionRequest extends Model
{
    use HasFactory;

    protected $table = 'session_requests';

    protected $fillable = [
        'learner_id',
        'instructor_id',
        'session_id',
        'type',
        'notes',
        'status',
        'rejection_reason',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
