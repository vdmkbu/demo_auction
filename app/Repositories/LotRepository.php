<?php


namespace App\Repositories;


use App\Models\Lot;

class LotRepository
{
    public function getOwnerLots(int $user_id)
    {
        return Lot::owner($user_id)->get();
    }
}
