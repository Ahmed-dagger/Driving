<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarDetail extends Model
{
    use HasFactory;

    protected $table = 'car_details';

    protected $fillable = [
        'instructor_id',
        'car_make',
        'car_model',
        'plate_number',
        'image',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
