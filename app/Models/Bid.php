<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    public function scopeMaxBid($query, $lot_id)
    {
        $query->where('lot_id', $lot_id)->max('bid');
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}
