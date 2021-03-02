<?php


namespace App\Services;


use App\Http\Requests\DTO\LotDto;
use App\Models\Lot;

class LotService
{
    public function create(LotDto $request): bool
    {
        $lot = new Lot();
        $lot->company_id = $request->getCompanyId();
        $lot->operation_type = $request->getOperationType();
        $lot->nomenclature = $request->getNomenclature();
        $lot->NDS = $request->getNDS();
        $lot->sum = $request->getSum();
        $lot->fee = $request->getFee();
        $lot->user_id = auth()->id();

        if (!$lot->save()) {
            return false;
        }

        return true;
    }

    public function edit(int $lot_id, LotDto $request): bool
    {
        $lot = Lot::find($lot_id);

        if (!$lot) {
            return false;
        }

        $lot->update([
            'company_id' => $request->getCompanyId(),
            'operation_type' => $request->getOperationType(),
            'nomenclature' => $request->getNomenclature(),
            'NDS' => $request->getNDS(),
            'sum' => $request->getSum(),
            'fee' => $request->getFee(),
        ]);

        return true;
    }
}
