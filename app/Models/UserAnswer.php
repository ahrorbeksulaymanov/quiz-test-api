<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'test_id', 'question_id', 'selected_option_id', 'is_correct'
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

    // Savol bilan aloqani o'rnatish
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Variant bilan aloqani o'rnatish
    public function option()
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }
}
