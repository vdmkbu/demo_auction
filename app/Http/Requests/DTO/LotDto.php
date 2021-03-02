<?php


namespace App\Http\Requests\DTO;


class LotDto
{
    private int $company_id;
    private int $operation_type;
    private string $nomenclature;
    private int $NDS;
    private float $sum;
    private float $fee;

    public function __construct($company_id, $operation_type, $nomenclature, $NDS, $sum, $fee)
    {
        $this->company_id = $company_id;
        $this->operation_type = $operation_type;
        $this->nomenclature = $nomenclature;
        $this->NDS = $NDS;
        $this->sum = $sum;
        $this->fee = $fee;
    }


    public function getCompanyId()
    {
        return $this->company_id;
    }


    public function getOperationType()
    {
        return $this->operation_type;
    }


    public function getNomenclature()
    {
        return $this->nomenclature;
    }


    public function getNDS()
    {
        return $this->NDS;
    }


    public function getSum()
    {
        return $this->sum;
    }


    public function getFee()
    {
        return $this->fee;
    }
}
