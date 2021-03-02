<?php


namespace App\Repositories;


use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyRepository
{

    public function getOwnerCompanies(int $user_id)
    {
        return Company::owner($user_id)->get();
    }

    public function isINNExists($inn)
    {
        return DB::table('companies')->where('INN','=', $inn)->count();
    }
}
