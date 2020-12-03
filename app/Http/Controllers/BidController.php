<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class BidController extends Controller
{
    public function store(Lot $lot, Request $request)
    {

        // еще раз проверим, что можем ставить ставку

        $user = User::find(auth()->id());
        $account = $user->account;

        // все деньги, которые зарезервированы под уже поставленные максимальные ставки
        $reserved = [];

        // получим все lot_id из ставок
        $lot_ids = DB::table('bids')->select('lot_id')->groupBy('lot_id')->get();
        foreach ($lot_ids as $lot_data) {
            $lot_id = $lot_data->lot_id;

            // для каждого лота получим максимальное значение ставки и id пользователя, который её поставил
            $data = DB::table('bids')->select(['user_id', 'bid'])->where('lot_id','=',$lot_id)->orderBy('bid', 'DESC')->limit(1)->get();

            foreach ($data as $item) {
                $user_id = $item->user_id;
                $bid = $item->bid;

                // если макс. ставка у текущего пользователя, то считаем сумму
                if ($user_id == auth()->id()) {

                    $sum = Lot::find($lot_id)->sum;
                    $reserved[] = ($sum/100) * $bid;

                }

            }
        }

        $reserved = array_sum($reserved);
        $free = $account - $reserved;

        $sum = $lot->sum;
        $comission = ($sum/100) * $request->Bid;

        if($free < $comission) {
            return response(['error' => "На вашем балансе недостаточно средств"],403);
        }

        // сохраняем ставку
        $bid = new Bid();
        $bid->bid = $request->Bid;
        $bid->lot_id = $request->Lot_ID;
        $bid->user_id = auth()->id();
        $bid->save();


        // отправляем письмо

    }
}
