<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeCategory extends Model
{
    /** @use HasFactory<\Database\Factories\AgeCategoryFactory> */
    use HasFactory;
    protected $fillable = ['title', 'from', 'to'];
}
