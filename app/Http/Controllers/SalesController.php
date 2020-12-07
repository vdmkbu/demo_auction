<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Lot;
use App\Models\User;
use App\Repositories\BidRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class SalesController extends Controller
{

    private UserRepository $userRepository;
    private BidRepository $bidRepository;

    public function __construct(UserRepository $userRepository, BidRepository $bidRepository)
    {
        $this->userRepository = $userRepository;
        $this->bidRepository = $bidRepository;
    }

    public function __invoke(Request $request)
    {
        $user = User::find(auth()->id());

        $lots = Lot::all()->where('operation_type', Lot::TYPE_SALE);
        $max_bids = $this->bidRepository->maxBids($lots);

        return view('lot.sales', [
            'lots' => $lots,
            'bids' => Bid::where('user_id', auth()->id())->get(),
            'max_bids' => $max_bids,
            'account' => $account = $this->userRepository->getUserAccount($user),
            'reserved' => $reserved = $this->userRepository->getReservedMoney($user),
            'free' => $this->userRepository->getFreeMoney($account, $reserved)
        ]);

    }
}
