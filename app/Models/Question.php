<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
    protected $fillable = [
        'title', 
        "description", 
        'photo', 
        'order', 
        'ball', 
        'level_id',
        'category_id',
        'correct_answer_id'
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }
}
