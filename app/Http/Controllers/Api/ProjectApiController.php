<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
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


    public function show(Project $project) {}


    public function edit(Project $project) {}


    public function update(Request $request, Project $project) {}


    public function destroy(Project $project) {}
}
