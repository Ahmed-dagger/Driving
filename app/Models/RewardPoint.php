<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RewardPoint extends Model
{
    use HasFactory;

    protected $table = 'reward_points';

    protected $fillable = [
        'user_id',
        'points',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
