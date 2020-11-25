<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class CompanyRepository
{

    public function isINNExists($inn)
    {
        return DB::table('companies')->where('INN','=', $inn)->count();
    }
}
