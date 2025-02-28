<?php

namespace App\Http\Controllers\Api\FreelanceFile\Projects;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserProjectController extends Controller
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
