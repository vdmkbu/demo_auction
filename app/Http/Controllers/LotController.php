<?php

namespace App\Http\Controllers;


use App\Http\Requests\LotStoreRequest;
use App\Http\Requests\LotUpdateRequest;
use App\Models\Lot;
use App\Repositories\BidRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\LotRepository;
use Illuminate\Support\Facades\Gate;


class LotController extends Controller
{
    private BidRepository $bidRepository;
    private LotRepository $lotRepository;
    private CompanyRepository $companyRepository;

    public function __construct(BidRepository $bidController,
                                LotRepository $lotRepository,
                                CompanyRepository $companyRepository)
    {
        $this->bidRepository = $bidController;
        $this->lotRepository = $lotRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $lots = $this->lotRepository->getOwnerLots(auth()->id());

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
        $companies = $this->companyRepository->getOwnerCompanies(auth()->id());
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


    public function store(LotStoreRequest $request)
    {

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

        $companies = $this->companyRepository->getOwnerCompanies(auth()->id());

        return view('lot.edit', [
            'lot' => $lot,
            'companies' => $companies
        ]);
    }


    public function update(LotUpdateRequest $request, Lot $lot)
    {
        if (Gate::denies('company_owner', $lot->user_id)) {
            abort(403, 'Access denied');
        }

        if ($lot->accepted_bid_id) {
            return redirect(route('lot.index'))->with('error', 'Редактирование запрещено, т.к. есть принятая ставка');
        }



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
