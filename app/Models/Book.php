<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'description',
        'category_id',
        'total_copies',
        'damaged_copies',
        'views',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}