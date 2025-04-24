<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasFactory;
    protected $fillable = ['title', "description", 'photo', 'order', 'ball', 'duration', 'age_category_id'];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
