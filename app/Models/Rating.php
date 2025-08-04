<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

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
