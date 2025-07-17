<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarDetail extends Model
{
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('car_images')->singleFile();
    }
}
