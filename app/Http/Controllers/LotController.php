<?php

namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\Lot;
use App\Repositories\BidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class LotController extends Controller
{
    private BidRepository $bidRepository;

    public function __construct(BidRepository $bidController)
    {
        $this->bidRepository = $bidController;
    }

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

        $max_bid = $this->bidRepository->getMaxBidByLot($lot);

        $lot->accepted_bid_id = $max_bid->id;
        $lot->save();

        return response()->json(['success' => "Ставка принята, лот закрыт"]);
    }
}
