<?php


namespace App\Services;


use App\Http\Requests\DTO\LotCreateDto;
use App\Models\Lot;

class LotService
{
    public function create(LotCreateDto $request): bool
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
}
