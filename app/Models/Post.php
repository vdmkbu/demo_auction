<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'announce',
        'text'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function scopeOrderByDate($query)
    {
        return $query->orderBy('date', 'desc');
    }
}
