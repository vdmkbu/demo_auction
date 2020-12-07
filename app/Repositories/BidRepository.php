<?php


namespace App\Repositories;


use App\Models\Lot;
use Illuminate\Support\Facades\DB;

class BidRepository
{
    public function maxBids($lots)
    {
        $max_bids = [];

        foreach($lots as $lot) {
            foreach($lot->bids as $bid) {

                $max_bid = $bid->maxBid($bid->lot_id)->max('bid');

                $max_bids[$bid->lot_id] = [
                    'max_bid' => $max_bid,
                    'next_fee' => $max_bid > $lot->fee ? $max_bid + 0.1 : $lot->fee
                ];
            }
        }

        return $max_bids;
    }

    public function getMaxBidByLot(Lot $lot)
    {
        $max_bid = DB::table('bids')
            ->select('id')
            ->where('lot_id','=',$lot->id)
            ->orderBy('bid', 'DESC')
            ->get()
            ->first();

        return $max_bid;
    }
}
