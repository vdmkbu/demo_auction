<?php

namespace App\Http\Controllers;

use App\Events\LotReceivedNewBid;
use App\Models\Bid;
use App\Models\Lot;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class BidController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(Lot $lot, Request $request)
    {

        // еще раз проверим, что можем ставить ставку

        $user = User::find(auth()->id());
        $account = $this->userRepository->getUserAccount($user);

        // все деньги, которые зарезервированы под уже поставленные максимальные ставки
        $reserved = $this->userRepository->getReservedMoney($user);
        $free = $this->userRepository->getFreeMoney($account, $reserved);

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
        event(new LotReceivedNewBid($bid, $lot, $comission));

    }
}
