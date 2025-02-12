<?php

namespace App\Http\Controllers\Api;


use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
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


    public function show(News $news) {}

    public function edit(News $news) {}


    public function update(Request $request, News $news) {}


    public function destroy(News $news) {}
}
