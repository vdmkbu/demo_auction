<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    public const TYPE_BUY = 1;
    public const TYPE_SALE = 2;

    protected $fillable = [
      'company_id',
      'operation_type',
      'NDS',
      'sum',
      'fee',
      'nomenclature',
      'user_id'
    ];

    public static function typesList()
    {
        return [self::TYPE_BUY, self::TYPE_SALE];
    }

    public function scopeOwner($query, $user_id)
    {
        $query->where('user_id', $user_id);
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
