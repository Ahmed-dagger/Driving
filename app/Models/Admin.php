<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable {
    use HasFactory, Notifiable,SoftDeletes;
    protected $table = 'admins';
    protected $fillable = ['name','email','password','phone', 'status', 'type'];
    protected $hidden = ['password','remember_token',];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string',
        'type' => 'string',
    ];
    public function profile(): HasOne {
        return $this->hasOne(related:AdminProfile::class, foreignKey:'admin_id');
    }
}
