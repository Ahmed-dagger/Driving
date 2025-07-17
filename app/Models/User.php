<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, SoftDeletes , InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'license_number',
        'experience_years',
        'bio',
        'status',
        'rate',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'experience_years' => 'integer',
    ];

    /**
     * Mutator to automatically hash password.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Check if user is an instructor.
     */
    public function isInstructor()
    {
        return $this->user_type === 'instructor';
    }

    /**
     * Check if user is a learner.
     */
    public function isLearner()
    {
        return $this->user_type === 'learner';
    }

      public function registerMediaCollections(): void
    {
        $this->addMediaCollection('car_images')->useDisk('public')->singleFile();
        $this->addMediaCollection('license_images')->useDisk('public')->singleFile();
        $this->addMediaCollection('profile_images')->useDisk('public')->singleFile();
    }
}
