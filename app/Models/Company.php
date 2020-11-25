<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'INN',
        'file',
        'OKVED',
        'date',
        'user_id'
    ];

    public function scopeOwner($query, $user_id)
    {
        $query->where('user_id', $user_id);
    }
}
