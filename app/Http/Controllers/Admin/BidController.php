<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function index()
    {

        $lots = Lot::has('bids')->get();

        return view('admin.bids.index', [
            'lots' => $lots
        ]);

    }

    public function show(Lot $lot)
    {
        $bids = Bid::with('user')->where('lot_id', $lot->id)->get();

        return view('admin.bids.bids', [
            'lot' => $lot,
            'bids' => $bids
        ]);
    }
}
