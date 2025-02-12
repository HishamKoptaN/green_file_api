<?php

namespace App\Http\Controllers\Api;


use App\Models\Company;
use Illuminate\Http\Request;

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
    public function index() {}


    public function create() {}


    public function store(Request $request) {}


    public function show(Company $company) {}


    public function edit(Company $company) {}


    public function update(Request $request, Company $company) {}


    public function destroy(Company $company) {}
}
