<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'test_id', 'start_time', 'end_time', 'time_spent', 'score'
    ];

    // Foydalanuvchi bilan aloqani o'rnatish
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Test bilan aloqani o'rnatish
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
