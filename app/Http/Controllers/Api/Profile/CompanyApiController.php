<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\User\Company;

class CompanyAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
}
