<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description',
        'days_count',
        'hours_count',
        'price',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
