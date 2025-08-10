<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'learner_id',
        'rating',
        'comment',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }
}
