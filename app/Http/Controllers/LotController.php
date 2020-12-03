<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Company;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class LotController extends Controller
{

    public function index()
    {
        $lots = Lot::owner(auth()->id())->get();

        $max_bids = [];
        foreach ($lots as $lot) {
            foreach ($lot->bids as $bid) {

                $max_bids[$bid->lot_id] = $bid->maxBid($bid->lot_id)->max('bid');

            }
        }

        return view('lot.index', [
            'lots' => $lots,
            'max_bids' => $max_bids
        ]);
    }


    public function create()
    {
        $companies = Company::owner(auth()->id())->get();
        return view('lot.create', [
            'companies' => $companies
        ]);
    }

    public function show(Lot $lot)
    {
        return view('lot.show', [
            'lot' => $lot
        ]);
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'company_id' => 'required|integer|exists:companies,id',
            'operation_type' => ['required', Rule::in(Lot::typesList())],
            'nomenclature' => 'required|max:3000',
            'NDS' => ['required', Rule::in([0, 10, 18])],
            'sum' => 'required|numeric',
            'fee' => 'required|numeric|between: 2,99',
        ]);

        Lot::create([
            'company_id' => $request->company_id,
            'operation_type' => $request->operation_type,
            'nomenclature' => $request->nomenclature,
            'NDS' => $request->NDS,
            'sum' => $request->sum,
            'fee' => $request->fee,
            'user_id' => auth()->id()
        ]);

        return redirect(route('lot.index'))->with('success', 'Лот добавлен');
    }


    public function edit(Lot $lot)
    {
        if (Gate::denies('company_owner', $lot->user_id)) {
            abort(403, 'Access denied');
        }

        if ($lot->accepted_bid_id) {
            return redirect(route('lot.index'))->with('error', 'Редактирование запрещено, т.к. есть принятая ставка');
        }

        $companies = Company::owner(auth()->id())->get();

        return view('lot.edit', [
            'lot' => $lot,
            'companies' => $companies
        ]);
    }


    public function update(Request $request, Lot $lot)
    {
        if (Gate::denies('company_owner', $lot->user_id)) {
            abort(403, 'Access denied');
        }

        if ($lot->accepted_bid_id) {
            return redirect(route('lot.index'))->with('error', 'Редактирование запрещено, т.к. есть принятая ставка');
        }

        $this->validate($request, [
            'company_id' => 'required|integer|exists:companies,id',
            'operation_type' => ['required', Rule::in(Lot::typesList())],
            'nomenclature' => 'required|max:3000',
            'NDS' => ['required', Rule::in([0, 10, 18])],
            'sum' => 'required|numeric',
            'fee' => 'required|numeric|between: 2,99',
        ]);

        $lot->update([
            'company_id' => $request->company_id,
            'operation_type' => $request->operation_type,
            'nomenclature' => $request->nomenclature,
            'NDS' => $request->NDS,
            'sum' => $request->sum,
            'fee' => $request->fee,
        ]);

        return redirect(route('lot.index'))->with('success', 'Лот изменен');
    }


    public function destroy(Lot $lot)
    {
        if (Gate::denies('company_owner', $lot->user_id)) {
            abort(403, 'Access denied');
        }

        if ($lot->accepted_bid_id) {
            return redirect(route('lot.index'))->with('error', 'Удаление запрещено, т.к. есть принятая ставка');
        }

        return $lot->delete();
    }

    public function bidAccept(Lot $lot)
    {

        if (Gate::denies('company_owner', $lot->user_id)) {
            abort(403, 'Access denied');
        }

        $max_bid = DB::table('bids')
            ->select('id')
            ->where('lot_id','=',$lot->id)
            ->orderBy('bid', 'DESC')
            ->get()
            ->first();


        $lot->accepted_bid_id = $max_bid->id;
        $lot->save();

        return response()->json(['success' => "Ставка принята, лот закрыт"]);
    }

    public function purchases()
    {

        $lots = Lot::all()->where('operation_type', Lot::TYPE_BUY);

        $max_bids = [];
        foreach($lots as $lot) {
            foreach($lot->bids as $bid) {

                $max_bid = $bid->maxBid($bid->lot_id)->max('bid');

                //$max_bids[$bid->lot_id] = $max_bid;
                $max_bids[$bid->lot_id] = [
                    'max_bid' => $max_bid,
                    'next_fee' => $max_bid > $lot->fee ? $max_bid + 0.1 : $lot->fee
                ];
            }
        }

        $user = User::find(auth()->id());
        $account = $user->account;


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
                if ($user_id == auth()->id()) {

                    $sum = Lot::find($lot_id)->sum;
                    $reserved[] = ($sum/100) * $bid;

                }

            }
        }

        $reserved = array_sum($reserved);

        $free = $account - $reserved;



        return view('lot.purchases', [
            'lots' => $lots,
            'bids' => Bid::where('user_id', auth()->id())->get(),
            'max_bids' => $max_bids,
            'account' => $account,
            'reserved' => $reserved,
            'free' => $free
        ]);


    }

    public function sales()
    {
        $lots = Lot::all()->where('operation_type', Lot::TYPE_SALE);

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

        $user = User::find(auth()->id());
        $account = $user->account;


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
                if ($user_id == auth()->id()) {

                    $sum = Lot::find($lot_id)->sum;
                    $reserved[] = ($sum/100) * $bid;

                }

            }
        }

        $reserved = array_sum($reserved);

        $free = $account - $reserved;


        return view('lot.sales', [
            'lots' => $lots,
            'bids' => Bid::where('user_id', auth()->id())->get(),
            'max_bids' => $max_bids,
            'account' => $account,
            'reserved' => $reserved,
            'free' => $free
        ]);
    }
}
