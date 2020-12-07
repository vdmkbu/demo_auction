<?php


namespace App\Repositories;


use App\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getUserAccount(User $user)
    {
        return $user->account;
    }

    public function getReservedMoney(User $user)
    {
        // все деньги, которые зарезервированы под уже поставленные максимальные ставки
        $reserved = [];

        // получим все lot_id из ставок
        $lot_ids = DB::table('bids')->select('lot_id')->groupBy('lot_id')->get();
        foreach ($lot_ids as $lot) {
            $lot_id = $lot->lot_id;

            // для каждого лота получим максимальное значение ставки и id пользователя, который её поставил
            $data = DB::table('bids')->select(['user_id', 'bid'])->where('lot_id','=',$lot_id)->orderBy('bid', 'DESC')->limit(1)->get();

            foreach ($data as $item) {
                $user_id = $item->user_id;
                $bid = $item->bid;

                // если макс. ставка у текущего пользователя, то считаем сумму
                if ($user_id == $user->id) {

                    $sum = Lot::find($lot_id)->sum;
                    $reserved[] = ($sum/100) * $bid;

                }

            }
        }

        $reserved = array_sum($reserved);

        return $reserved;
    }

    public function getFreeMoney($account, $reserved)
    {
        return $account - $reserved;
    }
}
