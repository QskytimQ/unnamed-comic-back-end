<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    protected $fillable = [
        'name', 'summary', 'author', 'types', 'cover', 'published_by', 'chapter_count', 'favorite_count',
    ];
}
